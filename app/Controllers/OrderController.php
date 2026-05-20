<?php
use App\Services\NotificationManager;
use App\Services\InAppNotifier;
class OrderController {
    
    // Khai báo thuộc tính $pdo cho Controller
    private $pdo;

    // Hàm khởi tạo: Tự động chạy ngay khi gọi OrderController
    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 3) {
            // 1. Nạp tin nhắn gắt gỏng vào Session để View hứng hiện Popup
            $_SESSION['error_msg'] = "Truy cập bị từ chối! Tài khoản của bạn không có thẩm quyền vào phân khu này.";
            // 2. Lấy đường link của trang trước đó, nếu trình duyệt không lưu thì mặc định về trang home
            $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
            // 3. chuyển người dùng quay lại nơi họ vừa đứng
            header("Location: " . $backUrl);
            exit();
        }
        // 1. LẤY CÁC THAM SỐ TỪ URL
        $current_status = $_GET['status'] ?? 'all';
        $search_text = trim($_GET['search_text'] ?? '');
        $search_type = $_GET['search_type'] ?? 'all';
        
        // Bắt thêm 2 tham số ngày tháng
        $from_date = $_GET['from_date'] ?? '';
        $to_date = $_GET['to_date'] ?? '';
        
        // Tham số phân trang
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($p < 1) $p = 1;
        $offset = ($p - 1) * $limit;

        $status_map = [
            'cho_duyet' => 'Chờ duyệt', 'tu_choi' => 'Duyệt từ chối', 'dong_y' => 'Duyệt đồng ý',
            'da_thanh_toan' => 'Đã thanh toán', 'dang_lam_hang' => 'Đang làm hàng', 
            'hoan_thanh' => 'Hoàn thành', 'cho_dung_don' => 'Chờ dừng đơn'
        ];

        // 2. XÂY DỰNG ĐIỀU KIỆN WHERE
        $where = " WHERE 1=1";
        $params = [];
        
        // Lọc theo Trạng thái
        if ($current_status !== 'all' && isset($status_map[$current_status])) {
            $where .= " AND status = ?";
            $params[] = $status_map[$current_status];
        }
        
        // LỌC THEO NGÀY THÁNG (Sử dụng DATE() để chỉ lấy ngày, bỏ qua giờ phút giây trong DB)
        if (!empty($from_date)) {
            $where .= " AND DATE(created_at) >= ?";
            $params[] = $from_date;
        }
        if (!empty($to_date)) {
            $where .= " AND DATE(created_at) <= ?";
            $params[] = $to_date;
        }

        // Lọc theo Từ khóa
        if (!empty($search_text)) {
            if ($search_type === 'order_code') {
                $where .= " AND order_code LIKE ?"; $params[] = "%$search_text%";
            } elseif ($search_type === 'creator_name') {
                $where .= " AND creator_name LIKE ?"; $params[] = "%$search_text%";
            } else {
                $where .= " AND (order_code LIKE ? OR creator_name LIKE ? OR bl_do_bkg LIKE ?)";
                array_push($params, "%$search_text%", "%$search_text%", "%$search_text%");
            }
        }

        // 3. ĐẾM TỔNG SỐ DÒNG (Để tính tổng số trang)
        $count_sql = "SELECT COUNT(*) FROM logis_orders" . $where;
        $stmt_count = $this->pdo->prepare($count_sql);
        $stmt_count->execute($params);
        $total_records = $stmt_count->fetchColumn();
        $total_pages = ceil($total_records / $limit);

        // 4. LẤY DỮ LIỆU CÓ GIỚI HẠN (LIMIT ... OFFSET ...)
        $sql = "SELECT o.*, 
        GROUP_CONCAT(CONCAT(d.quantity, 'x', p.container_type) SEPARATOR ', ') as container_details,
        SUM(d.quantity * p.price) as total_order_price
        FROM logis_orders o
        LEFT JOIN logis_order_details d ON o.id = d.order_id
        LEFT JOIN logis_pricing p ON d.pricing_id = p.id" 
        . $where . 
        " GROUP BY o.id ORDER BY o.id DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. ĐẾM SỐ LƯỢNG CHO CÁC NÚT TAB (Giữ nguyên)
        $count_stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM logis_orders GROUP BY status");
        $counts = [];
        while ($row = $count_stmt->fetch(PDO::FETCH_ASSOC)) { $counts[$row['status']] = $row['count']; }

        // 6. TÍNH TOÁN BẢNG SUM (Phiên bản Động - Tự động nhận diện mọi loại Cont)
        
        // 6.1. Lấy tất cả các loại Cont đang có trong bảng Giá để làm tiêu đề cột
        $stmtTypes = $this->pdo->query("SELECT container_type FROM logis_pricing ORDER BY id ASC");
        $containerTypes = $stmtTypes->fetchAll(PDO::FETCH_COLUMN); // Mảng chứa ['20 DC', '40 DC', '40 HC'...]

        // 6.2. Khởi tạo mảng thống kê chứa tất cả các loại Cont đó với giá trị ban đầu là 0
        $summary = [
            'ha_rong' => array_fill_keys($containerTypes, 0),
            'cap_rong' => array_fill_keys($containerTypes, 0)
        ];
        $summary['ha_rong']['tong'] = 0;
        $summary['cap_rong']['tong'] = 0;

        // 6.3. Vòng lặp đếm số lượng từ chuỗi (vd: "2x20 DC, 1x40 RF")
        foreach ($orders as $o) {
            $type = ($o['action_type'] == 'Hạ rỗng') ? 'ha_rong' : 'cap_rong';
            
            if (!empty($o['container_details'])) {
                $items = explode(',', $o['container_details']);
                foreach ($items as $item) {
                    $item = trim($item);
                    // Dùng Regex lấy số lượng (trước chữ x) và tên Loại Cont (sau chữ x)
                    if (preg_match('/^(\d+)x(.+)$/', $item, $matches)) {
                        $qty = (int)$matches[1];
                        $cType = trim($matches[2]); 
                        
                        // Nếu loại Cont này có tồn tại trong danh sách thì cộng dồn
                        if (array_key_exists($cType, $summary[$type])) {
                            $summary[$type][$cType] += $qty;
                            $summary[$type]['tong'] += $qty;
                        }
                    }
                }
            }
        }

        require_once 'app/Views/orders/index.php';
    }

    public function delete() {
        // Kiểm tra xem trên URL có truyền id lên không
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Dùng PDO prepare để chống lỗi bảo mật SQL Injection
            $stmt = $this->pdo->prepare("DELETE FROM logis_orders WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        // Xóa xong thì tự động quay ngoắt về lại trang danh sách đơn hàng
        header("Location: index.php?page=orders");
        exit();
    }

    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        // 1. Lấy thông tin chung của đơn hàng
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            die("Không tìm thấy đơn hàng.");
        }
        
        // 2. Lấy chi tiết các loại Cont của đơn hàng này từ bảng logis_order_details
        $stmtDetails = $this->pdo->prepare("SELECT * FROM logis_order_details WHERE order_id = ?");
        $stmtDetails->execute([$id]);
        $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Lấy danh sách 14 loại Cont từ bảng biểu giá để đổ vào thẻ <select>
        $stmtPricing = $this->pdo->query("SELECT * FROM logis_pricing ORDER BY id ASC");
        $pricings = $stmtPricing->fetchAll(PDO::FETCH_ASSOC);
        
        // Gọi ra View Edit
        require_once 'app/Views/orders/edit.php';
    }

    // BẠN DÁN HÀM UPDATE NÀY NGAY BÊN DƯỚI HÀM EDIT NHÉ
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            // 1. Cập nhật bảng chính (logis_orders)
            $stmt = $this->pdo->prepare("UPDATE logis_orders SET creator_name=?, action_type=?, depot_name=?, shipping_line=?, bl_do_bkg=?, note=?, status=? WHERE id=?");
            $stmt->execute([
                $_POST['creator_name'], 
                $_POST['action_type'], 
                $_POST['depot_name'], 
                $_POST['shipping_line'], 
                $_POST['bl_do_bkg'], 
                $_POST['note'], 
                $_POST['status'],
                $id
            ]);
            
            // 2. Xóa toàn bộ chi tiết Cont cũ của đơn này trong bảng logis_order_details
            $stmtDel = $this->pdo->prepare("DELETE FROM logis_order_details WHERE order_id = ?");
            $stmtDel->execute([$id]);
            
            // 3. Insert lại danh sách Cont mới từ Form gửi lên (từ Mảng containers)
            if (isset($_POST['containers']) && is_array($_POST['containers'])) {
                $stmtInsert = $this->pdo->prepare("INSERT INTO logis_order_details (order_id, pricing_id, quantity) VALUES (?, ?, ?)");
                foreach ($_POST['containers'] as $container) {
                    // Chỉ lưu nếu có ID bảng giá và số lượng > 0
                    if (!empty($container['pricing_id']) && !empty($container['quantity']) && $container['quantity'] > 0) {
                        $stmtInsert->execute([$id, $container['pricing_id'], $container['quantity']]);
                    }
                }
            }
            
            // Báo thành công và quay về trang danh sách
            $_SESSION['success_msg'] = "Đã cập nhật đơn hàng thành công!";
            header("Location: index.php?page=orders");
            exit;
        }
    }

    public function rejectOldPrice() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy lý do từ form
            $reason = $_POST['reject_reason'] ?? 'Từ chối do đơn giá cũ';
            
            // Lấy thông tin người duyệt và thời gian
            $approver_name = $_SESSION['username'] ?? 'Admin';
            $approval_date = date('Y-m-d H:i:s');
            $new_status = 'Duyệt từ chối';

            // Cập nhật Database: Chuyển toàn bộ đơn "Chờ duyệt" thành "Duyệt từ chối"
            // Đồng thời nối thêm Lý do vào cột Ghi chú (note)
            $sql = "UPDATE logis_orders 
                    SET status = ?, 
                        approver_name = ?, 
                        approval_date = ?, 
                        note = CONCAT(IFNULL(note, ''), ' [Lý do từ chối: ', ?, ']')
                    WHERE status = 'Chờ duyệt'";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$new_status, $approver_name, $approval_date, $reason]);
        }

        // Cập nhật xong thì đá về trang danh sách và mở sẵn tab "Duyệt từ chối" để xem kết quả
        header("Location: index.php?page=orders&status=tu_choi");
        exit();
    }

    public function changeStatus() {
        if (!isset($_GET['id']) || !isset($_GET['action'])) {
            header("Location: index.php?page=orders");
            exit();
        }

        $id = $_GET['id'];
        $action = $_GET['action'];
        
        $current_time = date('Y-m-d H:i:s');
        $user_name = $_SESSION['username'] ?? 'Admin'; 

        if ($action === 'approve') {
            $sql = "UPDATE logis_orders SET status = 'Duyệt đồng ý', approval_date = ?, approver_name = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$current_time, $user_name, $id]);
            $_SESSION['success_msg'] = "Đã duyệt đơn hàng thành công!";
            
            // ==========================================
            // TRIGGER THÔNG BÁO: ĐƠN HÀNG ĐÃ ĐƯỢC DUYỆT
            // ==========================================
            $this->sendNotificationToStaffs("Đơn hàng đã được duyệt", "Lệnh ID [$id] đã được phê duyệt bởi $user_name.");
            
        } elseif ($action === 'reject') {
            $sql = "UPDATE logis_orders SET status = 'Duyệt từ chối', approval_date = ?, approver_name = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$current_time, $user_name, $id]);
            $_SESSION['success_msg'] = "Đã từ chối đơn hàng!";
            
            // ==========================================
            // TRIGGER THÔNG BÁO: ĐƠN HÀNG BỊ TỪ CHỐI
            // ==========================================
            $this->sendNotificationToStaffs("Đơn hàng bị từ chối", "Lệnh ID [$id] đã bị $user_name từ chối duyệt.");
            
        } elseif ($action === 'complete') {
            $sql = "UPDATE logis_orders SET status = 'Hoàn thành', completion_date = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$current_time, $id]);
            $_SESSION['success_msg'] = "Xác nhận đơn hàng đã hoàn tất!";
        }

        $referer = $_SERVER['HTTP_REFERER'] ?? "index.php?page=orders";
        header("Location: " . $referer);
        exit();
    }

    // HÀM HỖ TRỢ: Gửi thông báo đến nhóm Staff (Role 3)
    // Bạn dán hàm này nằm ngay dưới hàm changeStatus() nhé
    private function sendNotificationToStaffs($title, $message) {
        $sqlGetStaff = "SELECT id FROM users WHERE role_id = 3 AND is_active = 1";
        $stmtUsers = $this->pdo->query($sqlGetStaff);
        $staffList = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

        if ($staffList) {
            $sqlInsertNotif = "INSERT INTO notifications (user_id, title, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
            $stmtNotif = $this->pdo->prepare($sqlInsertNotif);
            foreach ($staffList as $user) {
                $stmtNotif->execute([$user['id'], $title, $message]);
            }
            $sqlCleanup = "
                DELETE FROM notifications 
                WHERE user_id = ? 
                AND id NOT IN (
                    SELECT id FROM (
                        SELECT id FROM notifications 
                        WHERE user_id = ? 
                        ORDER BY id DESC 
                        LIMIT 50
                    ) AS tmp
                )
            ";
            $stmtCleanup = $this->pdo->prepare($sqlCleanup);
            foreach ($staffList as $user) {
                $stmtCleanup->execute([$user['id'], $user['id']]);
            }
        }
    }
}
?>
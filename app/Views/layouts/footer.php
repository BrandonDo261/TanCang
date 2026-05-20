</main> 
</div> 
</div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Xử lý nút bấm ẩn/hiện Sidebar
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        var sidebar = document.getElementById('sidebar');
        // Phải đồng bộ với độ rộng 260px trong CSS
        if (sidebar.style.marginLeft === '-260px') {
            sidebar.style.marginLeft = '0';
        } else {
            sidebar.style.marginLeft = '-260px';
        }
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Gọi ngay khi tải trang
    fetchNotifications();
    // Tự động quét tìm thông báo mới mỗi 3 giây (3000 ms)
    setInterval(fetchNotifications, 3000); 
});

function fetchNotifications() {
    // Gọi đến api.php để lấy dữ liệu từ Database
    fetch('<?= $baseUrl ?>/api.php?action=fetch')
        .then(res => res.json())
        .then(data => {
            const countEl = document.getElementById('notiCount');
            const listEl = document.getElementById('notiList');

            // 1. Cập nhật con số trên cái chuông
            if (data.unread_count > 0) {
                countEl.innerText = data.unread_count;
                countEl.style.display = 'inline-block';
            } else {
                countEl.style.display = 'none';
            }

            // 2. Đổ danh sách vào thẻ Dropdown
            if (data.notifications && data.notifications.length > 0) {
                let html = '';
                data.notifications.forEach(noti => {
                    // Nếu chưa đọc thì nền xanh, chữ in đậm
                    let bgClass = noti.is_read == 0 ? 'bg-light border-start border-primary border-4' : '';
                    let fwClass = noti.is_read == 0 ? 'fw-bold' : '';
                    
                    // Xử lý ngày tháng cho đẹp
                    let dateObj = new Date(noti.created_at);
                    let dateStr = dateObj.toLocaleDateString('vi-VN') + ' ' + dateObj.toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});

                    html += `
                        <div class="p-3 border-bottom ${bgClass}" onclick="markRead(${noti.id}, event)" style="cursor: pointer; transition: 0.2s;">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="${fwClass} text-dark" style="font-size: 14px;">${noti.title}</span>
                                <small class="text-muted" style="font-size: 11px;">${dateStr}</small>
                            </div>
                            <div class="text-muted" style="font-size: 13px;">${noti.message}</div>
                        </div>
                    `;
                });
                listEl.innerHTML = html;
            } else {
                listEl.innerHTML = '<div class="text-center p-4 text-muted small"><i class="fas fa-box-open fa-2x mb-2 text-gray-300"></i><br>Không có thông báo nào</div>';
            }
        })
        .catch(err => console.error("Lỗi tải thông báo:", err));
}

// Bấm vào 1 thông báo để đánh dấu đã đọc
function markRead(id, e) {
    e.stopPropagation(); // Ngăn dropdown bị đóng lại khi click
    fetch('<?= $baseUrl ?>/api.php?action=mark_read', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    }).then(() => fetchNotifications()); // Load lại giao diện chuông
}

// Nút Đánh dấu đã đọc tất cả
function markAllRead(e) {
    e.stopPropagation(); // Giữ cho popup không bị tắt khi bấm
    fetch('<?= $baseUrl ?>/api.php?action=mark_all_read', { method: 'POST' })
        .then(() => fetchNotifications());
}

// Nút Xóa tất cả
function deleteAllNoti(e) {
    e.stopPropagation();
    if(confirm('Bạn có chắc chắn muốn xóa sạch lịch sử thông báo?')) {
        fetch('<?= $baseUrl ?>/api.php?action=delete_all', { method: 'POST' })
            .then(() => fetchNotifications());
    }
}
</script>
</body>
</html>
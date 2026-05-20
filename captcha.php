<?php
session_start();

// 1. Tạo chuỗi ngẫu nhiên 5 ký tự (chữ hoa và số)
$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
$captcha_code = substr(str_shuffle($chars), 0, 5);

// 2. Lưu vào session để tí nữa so sánh với user nhập
$_SESSION['captcha_code'] = $captcha_code;

// 3. Khởi tạo bức ảnh kích thước 120x40 pixel
$image = imagecreate(120, 40);

// 4. Định nghĩa màu sắc
$bg_color = imagecolorallocate($image, 241, 245, 249); // Nền xám xanh nhạt
$text_color = imagecolorallocate($image, 2, 132, 199); // Chữ màu xanh dương (primary)
$line_color = imagecolorallocate($image, 148, 163, 184); // Màu xám cho các đường nhiễu

// 5. Vẽ các đường kẻ nhiễu (chống bot)
for($i = 0; $i < 5; $i++) {
    imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $line_color);
}

// 6. In chữ lên ảnh
imagestring($image, 5, 35, 12, $captcha_code, $text_color);

// 7. Xuất ra định dạng ảnh PNG
header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>
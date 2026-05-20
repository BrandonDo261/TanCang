// Scripts moved from app/Views/users/index.php
function fillEditModal(el) {
    document.getElementById('edit_id').value = el.getAttribute('data-id');
    document.getElementById('edit_username').value = el.getAttribute('data-username');
    document.getElementById('edit_full_name').value = el.getAttribute('data-fullname');
    document.getElementById('edit_email').value = el.getAttribute('data-email');
    document.getElementById('edit_phone').value = el.getAttribute('data-phone');
    document.getElementById('edit_role_id').value = el.getAttribute('data-role');
}

function fillResetModal(el) {
    document.getElementById('reset_id').value = el.getAttribute('data-id');
    document.getElementById('reset_username').innerText = '@' + el.getAttribute('data-username');
}

function validateAddUserForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    if (password !== confirmPassword) {
        alert('Mật khẩu đã nhập không khớp. Thử lại.');
        return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", function() {
    if(window.__user_msg) {
        const msgs = {
            'added': '🎉 Tạo tài khoản thành công!',
            'edited': '✅ Cập nhật thông tin thành công!',
            'password_reset': '🔑 Đã cấp lại mật khẩu mới thành công!',
            'status_changed': '🔄 Đã thay đổi trạng thái tài khoản!',
            'deleted': '🗑️ Đã xóa tài khoản vĩnh viễn!',
            'password_mismatch': '⚠️ Mật khẩu đã nhập không khớp. Thử lại.',
            'user_exists': '⚠️ Tên đăng nhập hoặc email đã tồn tại. Vui lòng thử lại.'
        };
        const msgType = window.__user_msg;
        if(msgs[msgType]) alert(msgs[msgType]);
        try { window.history.pushState({}, document.title, window.location.pathname + "?page=users"); } catch(e) {}
    }
});

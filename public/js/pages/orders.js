// Script moved from app/Views/orders/index.php
document.addEventListener('DOMContentLoaded', function() {
    const statusBtns = document.querySelectorAll('.filter-status');
    statusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            statusBtns.forEach(b => {
                b.classList.remove('btn-primary', 'fw-bold');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary', 'fw-bold');
        });
    });
});

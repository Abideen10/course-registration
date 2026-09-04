// =============================================
// Course Registration System — JavaScript
// =============================================

// =============================================
// ฟังก์ชันยืนยันการลบ (Confirm Delete)
// =============================================
// เรียกใช้ตอนกดปุ่ม Delete
// ถ้า user กด OK -> return true -> form จะ submit
// ถ้า user กด Cancel -> return false -> form จะไม่ submit
function confirmDelete(itemName) {
    return confirm('คุณต้องการลบ "' + itemName + '" ใช่หรือไม่?\n\nการลบจะไม่สามารถกู้คืนได้');
}

// =============================================
// เปิด/ปิด Modal (Popup Form)
// =============================================
function openModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

// ปิด Modal เมื่อคลิกพื้นหลัง
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
});

// =============================================
// ซ่อน Alert อัตโนมัติหลัง 4 วินาที
// =============================================
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 4000);
    });
});

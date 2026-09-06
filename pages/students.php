<?php
// =============================================
// students.php — หน้าจัดการนักศึกษา (Student Management)
// =============================================
// หน้านี้ทำงาน 2 อย่าง:
// 1. แสดงรายชื่อนักศึกษาทั้งหมด
// 2. แสดง Modal Form สำหรับเพิ่ม/แก้ไขนักศึกษา

// ตรวจสอบสิทธิ์: เฉพาะ Admin เท่านั้น
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();


require_once __DIR__ . '/../config/database.php';

// =============================================
// รับข้อความ success/error จาก URL (Query String)
// =============================================
// เมื่อเพิ่ม/แก้ไข/ลบ สำเร็จ action file จะ redirect กลับมาพร้อม ?success=...
// เช่น students.php?success=เพิ่มนักศึกษาสำเร็จ
//
// $_GET คือ array ที่เก็บค่าจาก URL query string
// เปรียบเทียบกับ JavaScript:
// JS:  const params = new URLSearchParams(window.location.search);
//      const success = params.get('success');
// PHP: $success = $_GET['success'];
//
// ?? คือ null coalescing operator (PHP 7+)
// เหมือน ?? ใน JavaScript:
// JS:  const success = params.get('success') ?? '';
// PHP: $success = $_GET['success'] ?? '';
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// =============================================
// ตรวจสอบว่ากำลังแก้ไข (Edit Mode) หรือไม่
// =============================================
// ถ้า URL มี ?edit=3 หมายความว่ากำลังแก้ไขนักศึกษา id=3
// isset() = ตรวจว่าตัวแปรมีค่าหรือไม่ (เหมือนเช็ค !== undefined ใน JS)
$editStudent = null;
if (isset($_GET['edit'])) {
    // =============================================
    // Prepared Statement — ป้องกัน SQL Injection
    // =============================================
    // [อันตราย] (SQL Injection):
    // $pdo->query("SELECT * FROM students WHERE id = " . $_GET['edit']);
    //
    // [ปลอดภัย] (Prepared Statement):
    // prepare() = เตรียม SQL ไว้ก่อน โดยใช้ ? เป็น placeholder
    // execute() = ใส่ค่าจริงเข้าไปแทน ?
    //
    // เหมือนการแยก "คำสั่ง" กับ "ข้อมูล" ออกจากกัน
    // Database จะรู้ว่า ? คือ "ข้อมูล" ไม่ใช่ "คำสั่ง SQL"
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['edit']]);

    // fetch() = ดึงผลลัพธ์ 1 แถว (ต่างจาก fetchAll() ที่ดึงทั้งหมด)
    $editStudent = $stmt->fetch();
}

// =============================================
// ดึงข้อมูลนักศึกษาทั้งหมด
// =============================================
$stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
$students = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">

    <!-- Alert Messages (ถ้า $success ไม่ใช่ค่า null ให้ทำใน if success// ถ้า $error ไม่ใช่ค่า null ให้ทำใน if error)-->
    <!-- รับค่าจากบรรทัดที่ 27/28 เพื่อแสดง alert -->
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fa-solid fa-user-graduate"></i></span>
            Student Management
        </h1>
        <button class="btn btn-primary" onclick="openModal('studentModal')">
            <i class="fa-solid fa-user-plus"></i> เพิ่มนักศึกษา
        </button>
    </div>

    <!-- ตารางนักศึกษา -->
    <div class="card">
        <?php if (count($students) > 0): ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>สาขาวิชา</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= $student['id'] ?></td>
                                <td><?= htmlspecialchars($student['student_code']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td><?= htmlspecialchars($student['department']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- ปุ่ม Edit: ส่ง ?edit=id กลับมาหน้านี้เอง -->
                                        <a href="students.php?edit=<?= $student['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen"></i> แก้ไข
                                        </a>

                                        <!-- ปุ่ม Delete: ใช้ form POST ส่งไป student_delete.php -->
                                        <!--
                                    ทำไมใช้ form POST แทน link GET สำหรับ Delete?
                                    เพราะ GET ควรใช้สำหรับ "ดูข้อมูล" เท่านั้น
                                    การ "ลบข้อมูล" ควรใช้ POST เพื่อป้องกันการลบโดยไม่ตั้งใจ
                                    (เช่น bot ที่ crawl ลิงก์ทุกลิงก์ในหน้าเว็บ)
                                -->
                                        <form method="POST" action="../actions/student_delete.php" style="display:inline;"
                                            onsubmit="return confirmDelete('<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>')">
                                            <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fa-solid fa-trash-can"></i> ลบ</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <p>ยังไม่มีข้อมูลนักศึกษา กดปุ่ม "เพิ่มนักศึกษา" เพื่อเริ่มต้น</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มเพิ่มนักศึกษา
     ============================================= -->
<!--
    Modal คือ popup form ที่แสดงทับหน้าเว็บ
    เปิด/ปิดด้วย JavaScript (openModal / closeModal ใน app.js)
-->
<div id="studentModal" class="modal-overlay">
    <div class="modal">
        <h2 class="modal-title"><i class="fa-solid fa-user-plus"></i> เพิ่มนักศึกษาใหม่</h2>

        <!--
            HTML Form: ส่งข้อมูลไป PHP
            method="POST" = ส่งข้อมูลแบบซ่อน (ไม่แสดงใน URL)
            action="..." = URL ของ PHP ที่จะรับข้อมูล
            
            เปรียบเทียบกับ JavaScript:
            JS:  fetch('/api/students', { method: 'POST', body: formData })
            PHP: <form method="POST" action="student_create.php">
            
            ทั้งสองส่งข้อมูลไปที่ server เหมือนกัน
            แต่ HTML Form จะ reload หน้าเว็บ (ซึ่งเหมาะสำหรับผู้เริ่มต้น)
        -->
        <form method="POST" action="../actions/student_create.php">
            <div class="form-group">
                <label for="student_code">รหัสนักศึกษา</label>
                <input type="text" id="student_code" name="student_code" class="form-control" placeholder="เช่น 65001"
                    required>
            </div>
            <div class="form-group">
                <label for="name">ชื่อ-นามสกุล</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="เช่น สมชาย ใจดี" required>
            </div>
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="เช่น somchai@university.ac.th" required>
            </div>
            <div class="form-group">
                <label for="department">สาขาวิชา</label>
                <input type="text" id="department" name="department" class="form-control"
                    placeholder="เช่น วิทยาการคอมพิวเตอร์" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('studentModal')">ยกเลิก</button>
            </div>
        </form>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มแก้ไขนักศึกษา
     ============================================= -->
<!--
    Modal นี้จะเปิดอัตโนมัติเมื่อ URL มี ?edit=id
    ข้อมูลเดิมจะถูกเติมใน form ให้แก้ไข
-->
<?php if ($editStudent): ?>
    <div id="editModal" class="modal-overlay active">
        <div class="modal">
            <h2 class="modal-title"><i class="fa-solid fa-user-pen"></i> แก้ไขนักศึกษา</h2>

            <form method="POST" action="../actions/student_update.php">
                <!--
                input hidden: ส่ง id ไปด้วย แต่ไม่แสดงบนหน้าจอ
                PHP ฝั่ง server จะใช้ id นี้เพื่อรู้ว่าจะแก้ไขแถวไหน
            -->
                <input type="hidden" name="id" value="<?= $editStudent['id'] ?>">
                <div class="form-group">
                    <label for="edit_student_code">รหัสนักศึกษา</label>
                    <input type="text" id="edit_student_code" name="student_code" class="form-control"
                        value="<?= htmlspecialchars($editStudent['student_code']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_name">ชื่อ-นามสกุล</label>
                    <input type="text" id="edit_name" name="name" class="form-control"
                        value="<?= htmlspecialchars($editStudent['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">อีเมล</label>
                    <input type="email" id="edit_email" name="email" class="form-control"
                        value="<?= htmlspecialchars($editStudent['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_department">สาขาวิชา</label>
                    <input type="text" id="edit_department" name="department" class="form-control"
                        value="<?= htmlspecialchars($editStudent['department']) ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> อัพเดต</button>
                    <a href="students.php" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
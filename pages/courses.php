<?php
// =============================================
// courses.php — หน้าจัดการรายวิชา (Course Management)
// =============================================
// โครงสร้างเหมือน students.php เลย:
// 1. เชื่อมต่อ DB
// 2. รับ success/error message
// 3. ตรวจสอบ edit mode
// 4. ดึงข้อมูลทั้งหมด
// 5. แสดง HTML (ตาราง + Modal)

// ตรวจสอบสิทธิ์: ต้อง login ก่อน
require_once __DIR__ . '/../includes/auth.php';
requireLogin();


require_once __DIR__ . '/../config/database.php';

// รับข้อความ success/error
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// ตรวจสอบ Edit Mode
$editCourse = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCourse = $stmt->fetch();
}

// ดึงรายวิชาทั้งหมด
$stmt = $pdo->query("SELECT * FROM courses ORDER BY id DESC");
$courses = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">

    <!-- Alert Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fa-solid fa-book"></i></span>
            Course Management
        </h1>
        <?php if (isAdmin()): ?>
            <button class="btn btn-primary" onclick="openModal('courseModal')">
                <i class="fa-solid fa-plus"></i> เพิ่มรายวิชา
            </button>
        <?php endif; ?>
    </div>

    <!-- ตารางรายวิชา -->
    <div class="card">
        <?php if (count($courses) > 0): ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>รหัสวิชา</th>
                            <th>ชื่อรายวิชา</th>
                            <th>หน่วยกิต</th>
                            <th>ผู้สอน</th>
                            <?php if (isAdmin()): ?>
                                <th>จัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= htmlspecialchars($course['course_code']) ?></td>
                                <td><?= htmlspecialchars($course['course_name']) ?></td>
                                <td><?= $course['credits'] ?></td>
                                <td><?= htmlspecialchars($course['teacher']) ?></td>
                                <?php if (isAdmin()): ?>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="courses.php?edit=<?= $course['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fa-solid fa-pen"></i> แก้ไข
                                            </a>
                                            <form method="POST" action="../actions/course_delete.php" style="display:inline;"
                                                onsubmit="return confirmDelete('<?= htmlspecialchars($course['course_name'], ENT_QUOTES) ?>')">
                                                <input type="hidden" name="id" value="<?= $course['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can"></i> ลบ</button>
                                            </form>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-book-open"></i></div>
                <p>ยังไม่มีข้อมูลรายวิชา กดปุ่ม "เพิ่มรายวิชา" เพื่อเริ่มต้น</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มเพิ่มรายวิชา
     ============================================= -->

<div id="courseModal" class="modal-overlay">
    <div class="modal">
        <h2 class="modal-title"><i class="fa-solid fa-plus"></i> เพิ่มรายวิชาใหม่</h2>
        <form method="POST" action="../actions/course_create.php">
            <div class="form-group">
                <label for="course_code">รหัสวิชา</label>
                <input type="text" id="course_code" name="course_code" class="form-control" placeholder="เช่น CS101"
                    required>
            </div>
            <div class="form-group">
                <label for="course_name">ชื่อรายวิชา</label>
                <input type="text" id="course_name" name="course_name" class="form-control"
                    placeholder="เช่น การเขียนโปรแกรมเบื้องต้น" required>
            </div>
            <div class="form-group">
                <label for="credits">หน่วยกิต</label>
                <!--
                        type="number" = บังคับให้กรอกเฉพาะตัวเลข
                        min="1" max="6" = กำหนดขอบเขตที่ Browser ตรวจสอบ
                        
                        แต่! การ validate ที่ Browser เป็นแค่ "ความสะดวก"
                        ต้อง validate ที่ PHP ด้วย เพราะ user สามารถแก้ HTML ได้
                    -->
                <input type="number" id="credits" name="credits" class="form-control" placeholder="เช่น 3" min="1"
                    max="6" required>
            </div>
            <div class="form-group">
                <label for="teacher">ผู้สอน</label>
                <input type="text" id="teacher" name="teacher" class="form-control" placeholder="เช่น ผศ.ดร.สมศักดิ์"
                    required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('courseModal')">ยกเลิก</button>
            </div>
        </form>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มแก้ไขรายวิชา
     ============================================= -->
<?php if ($editCourse): ?>
    <div id="editCourseModal" class="modal-overlay active">
        <div class="modal">
            <h2 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> แก้ไขรายวิชา</h2>
            <form method="POST" action="../actions/course_update.php">
                <input type="hidden" name="id" value="<?= $editCourse['id'] ?>">
                <div class="form-group">
                    <label for="edit_course_code">รหัสวิชา</label>
                    <input type="text" id="edit_course_code" name="course_code" class="form-control"
                        value="<?= htmlspecialchars($editCourse['course_code']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_course_name">ชื่อรายวิชา</label>
                    <input type="text" id="edit_course_name" name="course_name" class="form-control"
                        value="<?= htmlspecialchars($editCourse['course_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit_credits">หน่วยกิต</label>
                    <input type="number" id="edit_credits" name="credits" class="form-control"
                        value="<?= $editCourse['credits'] ?>" min="1" max="6" required>
                </div>
                <div class="form-group">
                    <label for="edit_teacher">ผู้สอน</label>
                    <input type="text" id="edit_teacher" name="teacher" class="form-control"
                        value="<?= htmlspecialchars($editCourse['teacher']) ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> อัพเดต</button>
                    <a href="courses.php" class="btn btn-secondary">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
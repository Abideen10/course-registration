<?php
// =============================================
// enrollments.php — หน้าจัดการลงทะเบียนเรียน (Enrollment Management)
// =============================================
// หน้านี้ต่างจาก students.php ตรงที่:
// 1. ต้อง JOIN ตาราง เพื่อแสดงชื่อแทน id
// 2. Form ใช้ <select> dropdown แทน <input> text
// 3. Edit แก้ไขเฉพาะ grade

require_once __DIR__ . '/../config/database.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// =============================================
// ดึงข้อมูลสำหรับ Dropdown (ใช้ในฟอร์มเพิ่ม)
// =============================================
// ดึงรายชื่อนักศึกษาทั้งหมด -> ใช้ใน <select> เลือกนักศึกษา
// ดึงรายวิชาทั้งหมด -> ใช้ใน <select> เลือกรายวิชา
$allStudents = $pdo->query("SELECT * FROM students ORDER BY student_code")->fetchAll();
$allCourses = $pdo->query("SELECT * FROM courses ORDER BY course_code")->fetchAll();

// =============================================
// ตรวจสอบ Edit Mode
// =============================================
$editEnrollment = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editEnrollment = $stmt->fetch();
}

// =============================================
// ดึงข้อมูลลงทะเบียนทั้งหมด (พร้อม JOIN)
// =============================================
// JOIN คืออะไร?
// ปกติ enrollments เก็บแค่ student_id=1, course_id=2
// แต่เราอยากแสดง "ชื่อนักศึกษา" และ "ชื่อรายวิชา"
// JOIN = เอาข้อมูลจากหลายตารางมารวมกันเป็นแถวเดียว
//
// เปรียบเทียบกับ JavaScript:
// สมมติมี 2 arrays:
//   const students = [{id:1, name:'John'}, {id:2, name:'Jane'}];
//   const enrollments = [{student_id:1, course_id:2}];
//
// JS: enrollments.map(e => ({
//       ...e,
//       student_name: students.find(s => s.id === e.student_id).name
//     }));
//
// SQL ทำเรื่องเดียวกัน แต่ให้ Database จัดการ (เร็วกว่ามาก):
//   SELECT e.*, s.name AS student_name
//   FROM enrollments e
//   JOIN students s ON e.student_id = s.id
//
// e.* = ทุกคอลัมน์จาก enrollments
// s.name AS student_name = เอา name จาก students มาเรียกว่า student_name
// ON e.student_id = s.id = เงื่อนไขการเชื่อม (student_id ต้อง = id)

$stmt = $pdo->query("
    SELECT 
        e.id,
        e.student_id,
        e.course_id,
        e.enrollment_date,
        e.grade,
        s.student_code,
        s.name AS student_name,
        c.course_code,
        c.course_name
    FROM enrollments e
    JOIN students s ON e.student_id = s.id
    JOIN courses c ON e.course_id = c.id
    ORDER BY e.id DESC
");
$enrollments = $stmt->fetchAll();

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
            <span class="page-title-icon"><i class="fa-solid fa-clipboard-list"></i></span>
            Enrollment Management
        </h1>
        <button class="btn btn-primary" onclick="openModal('enrollmentModal')">
            <i class="fa-solid fa-plus"></i> ลงทะเบียนเรียน
        </button>
    </div>

    <!-- ตารางลงทะเบียน -->
    <div class="card">
        <?php if (count($enrollments) > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>นักศึกษา</th>
                        <th>รายวิชา</th>
                        <th>วันที่ลงทะเบียน</th>
                        <th>เกรด</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $enrollment): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($enrollment['student_code']) ?> -
                            <?= htmlspecialchars($enrollment['student_name']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($enrollment['course_code']) ?> -
                            <?= htmlspecialchars($enrollment['course_name']) ?>
                        </td>
                        <td><?= htmlspecialchars($enrollment['enrollment_date']) ?></td>
                        <td>
                            <?php if ($enrollment['grade']): ?>
                                <?php
                                $gradeClass = 'grade-none';
                                $firstChar = strtolower(substr($enrollment['grade'], 0, 1));
                                if ($firstChar === 'a') $gradeClass = 'grade-a';
                                elseif ($firstChar === 'b') $gradeClass = 'grade-b';
                                elseif ($firstChar === 'c') $gradeClass = 'grade-c';
                                elseif ($firstChar === 'd') $gradeClass = 'grade-d';
                                elseif ($firstChar === 'f') $gradeClass = 'grade-f';
                                ?>
                                <span class="grade-badge <?= $gradeClass ?>">
                                    <?= htmlspecialchars($enrollment['grade']) ?>
                                </span>
                            <?php else: ?>
                                <span class="grade-badge grade-none">ยังไม่มี</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="enrollments.php?edit=<?= $enrollment['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i> แก้ไข
                                </a>
                                <form method="POST" action="../actions/enrollment_delete.php" style="display:inline;"
                                      onsubmit="return confirmDelete('<?= htmlspecialchars($enrollment['student_name'] . ' - ' . $enrollment['course_name'], ENT_QUOTES) ?>')">
                                    <input type="hidden" name="id" value="<?= $enrollment['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> ยกเลิก</button>
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
                <div class="empty-state-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                <p>ยังไม่มีข้อมูลการลงทะเบียน กดปุ่ม "ลงทะเบียนเรียน" เพื่อเริ่มต้น</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มลงทะเบียน
     ============================================= -->
<div id="enrollmentModal" class="modal-overlay">
    <div class="modal">
        <h2 class="modal-title"><i class="fa-solid fa-plus"></i> ลงทะเบียนเรียน</h2>
        <form method="POST" action="../actions/enrollment_create.php">

            <!-- Dropdown เลือกนักศึกษา -->
            <!--
                <select> + PHP foreach = Dropdown ที่สร้างจากข้อมูล Database
                
                เปรียบเทียบกับ JavaScript:
                JS: students.forEach(s => {
                      select.innerHTML += `<option value="${s.id}">${s.name}</option>`;
                    });
                
                PHP ผสม HTML ได้ตรงๆ:
                <select>
                  foreach ($students as $s):
                    <option value="$s['id']">$s['name']</option>
                  endforeach;
                </select>
            -->
            <div class="form-group">
                <label for="student_id">นักศึกษา</label>
                <select id="student_id" name="student_id" class="form-control" required>
                    <option value="">-- เลือกนักศึกษา --</option>
                    <?php foreach ($allStudents as $student): ?>
                        <option value="<?= $student['id'] ?>">
                            <?= htmlspecialchars($student['student_code'] . ' - ' . $student['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Dropdown เลือกรายวิชา -->
            <div class="form-group">
                <label for="course_id">รายวิชา</label>
                <select id="course_id" name="course_id" class="form-control" required>
                    <option value="">-- เลือกรายวิชา --</option>
                    <?php foreach ($allCourses as $course): ?>
                        <option value="<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- วันที่ลงทะเบียน -->
            <!--
                date('Y-m-d') = วันที่ปัจจุบันในรูปแบบ YYYY-MM-DD
                เหมือน JS: new Date().toISOString().split('T')[0]
            -->
            <div class="form-group">
                <label for="enrollment_date">วันที่ลงทะเบียน</label>
                <input type="date" id="enrollment_date" name="enrollment_date" class="form-control"
                       value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> ลงทะเบียน</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('enrollmentModal')">ยกเลิก</button>
            </div>
        </form>
    </div>
</div>

<!-- =============================================
     Modal: ฟอร์มแก้ไข Grade
     ============================================= -->
<!--
    Enrollment edit แก้ไขเฉพาะ "grade"
    ไม่ให้เปลี่ยนนักศึกษาหรือรายวิชา (แสดงเป็นข้อมูลให้ดูอย่างเดียว)
-->
<?php if ($editEnrollment): ?>
    <?php
    // ดึงชื่อนักศึกษาและรายวิชาสำหรับแสดงใน form
    $stmtStudent = $pdo->prepare("SELECT student_code, name FROM students WHERE id = ?");
    $stmtStudent->execute([$editEnrollment['student_id']]);
    $enrollStudent = $stmtStudent->fetch();

    $stmtCourse = $pdo->prepare("SELECT course_code, course_name FROM courses WHERE id = ?");
    $stmtCourse->execute([$editEnrollment['course_id']]);
    $enrollCourse = $stmtCourse->fetch();
    ?>
<div id="editEnrollmentModal" class="modal-overlay active">
    <div class="modal">
        <h2 class="modal-title"><i class="fa-solid fa-pen-to-square"></i> แก้ไขเกรด</h2>
        <form method="POST" action="../actions/enrollment_update.php">
            <input type="hidden" name="id" value="<?= $editEnrollment['id'] ?>">

            <!-- แสดงข้อมูลให้ดู (ไม่สามารถแก้ไข) -->
            <div class="form-group">
                <label>นักศึกษา</label>
                <input type="text" class="form-control" disabled
                       value="<?= htmlspecialchars($enrollStudent['student_code'] . ' - ' . $enrollStudent['name']) ?>">
            </div>
            <div class="form-group">
                <label>รายวิชา</label>
                <input type="text" class="form-control" disabled
                       value="<?= htmlspecialchars($enrollCourse['course_code'] . ' - ' . $enrollCourse['course_name']) ?>">
            </div>

            <!-- แก้ไข Grade -->
            <!--
                <select> สำหรับเลือกเกรด
                
                Concept ใหม่: selected attribute
                ถ้า option มี attribute "selected" มันจะถูกเลือกเป็นค่าเริ่มต้น
                
                เราใช้ PHP เปรียบเทียบว่า grade ปัจจุบันตรงกับ option ไหน
                ถ้าตรง -> เพิ่ม "selected"
                
                === ใน PHP ทำงานเหมือน === ใน JavaScript เลย
                (เปรียบเทียบทั้งค่าและชนิดข้อมูล)
            -->
            <div class="form-group">
                <label for="edit_grade">เกรด</label>
                <select id="edit_grade" name="grade" class="form-control">
                    <option value="" <?= $editEnrollment['grade'] === null ? 'selected' : '' ?>>ยังไม่มี</option>
                    <option value="A" <?= $editEnrollment['grade'] === 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B+" <?= $editEnrollment['grade'] === 'B+' ? 'selected' : '' ?>>B+</option>
                    <option value="B" <?= $editEnrollment['grade'] === 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C+" <?= $editEnrollment['grade'] === 'C+' ? 'selected' : '' ?>>C+</option>
                    <option value="C" <?= $editEnrollment['grade'] === 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D+" <?= $editEnrollment['grade'] === 'D+' ? 'selected' : '' ?>>D+</option>
                    <option value="D" <?= $editEnrollment['grade'] === 'D' ? 'selected' : '' ?>>D</option>
                    <option value="F" <?= $editEnrollment['grade'] === 'F' ? 'selected' : '' ?>>F</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> อัพเดต</button>
                <a href="enrollments.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

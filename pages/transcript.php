<?php

// ตรวจสอบสิทธิ์: ต้อง login ก่อน
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

require_once __DIR__ . '/../config/database.php';

if (isStudent()) {
    // ถ้านักศึกษาเปิดดู ให้ดึงเฉพาะผลการเรียนของตัวเอง
    $sql = $pdo->prepare("
        SELECT
            u.student_code,
            u.name,
            c.course_code,
            c.course_name,
            c.credits,
            c.teacher,
            e.grade
        FROM users u
        JOIN enrollments e ON u.id = e.user_id
        JOIN courses c ON c.id = e.course_id
        WHERE u.id = ?
        ORDER BY c.course_code ASC
    ");
    $sql->execute([$_SESSION['user_id']]);
} else {
    // ถ้าเป็น Admin ให้ดูผลการเรียนของนักศึกษาทุกคน
    $sql = $pdo->query("
        SELECT
            u.student_code,
            u.name,
            c.course_code,
            c.course_name,
            c.credits,
            c.teacher,
            e.grade
        FROM users u
        JOIN enrollments e ON u.id = e.user_id
        JOIN courses c ON c.id = e.course_id
        ORDER BY u.student_code ASC, c.course_code ASC
    ");
}
$transcripts = $sql->fetchAll();


require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';

?>

<div class="main-content">

    <div class="card">
        <h2 style="font-size: 1.15rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-book"></i> แสดงผลการเรียน
        </h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>รหัสนักศึกษา</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>รหัสวิชา</th>
                        <th>ชื่อวิชา</th>
                        <th>หน่วยกิต</th>
                        <th>อาจารย์ผู้สอน</th>
                        <th>เกรดที่ได้</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transcripts as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['student_code']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['course_code']) ?></td>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><?= htmlspecialchars($row['credits']) ?></td>
                            <td><?= htmlspecialchars($row['teacher']) ?></td>
                            <td>
                                <?php if ($row['grade']): ?>
                                    <?php
                                    $gradeClass = 'grade-none';
                                    $firstChar = strtolower(substr($row['grade'], 0, 1));
                                    if ($firstChar === 'a')
                                        $gradeClass = 'grade-a';
                                    elseif ($firstChar === 'b')
                                        $gradeClass = 'grade-b';
                                    elseif ($firstChar === 'c')
                                        $gradeClass = 'grade-c';
                                    elseif ($firstChar === 'd')
                                        $gradeClass = 'grade-d';
                                    elseif ($firstChar === 'f')
                                        $gradeClass = 'grade-f';
                                    ?>
                                    <span class="grade-badge <?= $gradeClass ?>"><?= htmlspecialchars($row['grade']) ?></span>
                                <?php else: ?>
                                    <span class="grade-badge grade-none">ยังไม่มี</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
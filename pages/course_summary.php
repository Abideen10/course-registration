<?php
require_once __DIR__ . '/../config/database.php';

// 🎯 เขียน SQL ของคุณตรงนี้ (ใช้ LEFT JOIN, COUNT, GROUP BY)
$sql = $pdo->query("
    SELECT
        c.course_code,
        c.course_name,
        c.credits,
        c.teacher,
        COUNT(e.id) AS total_enrollments
    FROM courses c
    LEFT JOIN enrollments e ON c.id = e.course_id
    GROUP BY c.id
");
$courseSummaries = $sql->fetchAll();

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <div class="card">
        <h2 style="font-size: 1.15rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-chart-pie"></i> สรุปยอดผู้ลงทะเบียนแต่ละวิชา
        </h2>

        <!-- ตารางแสดงผล -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>รหัสวิชา</th>
                        <th>ชื่อวิชา</th>
                        <th>หน่วยกิต</th>
                        <th>อาจารย์ผู้สอน</th>
                        <th>จำนวนผู้ลงทะเบียน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courseSummaries as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['course_code']) ?></td>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><?= htmlspecialchars($row['credits']) ?></td>
                            <td><?= htmlspecialchars($row['teacher']) ?></td>
                            <td><?= htmlspecialchars($row['total_enrollments']) ?> คน</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<?php
// =============================================
// index.php — Dashboard (หน้าแรก)
// =============================================

// ตรวจสอบสิทธิ์: ต้อง login ก่อนเข้าหน้านี้
require_once __DIR__ . '/includes/auth.php';
requireLogin();

// เชื่อมต่อ Database
// require_once = นำเข้าไฟล์อื่นเข้ามา (เหมือน import ใน JS)
// __DIR__ = path ของไฟล์นี้ (เหมือน __dirname ใน Node.js)
// หลังจากบรรทัดนี้ ตัวแปร $pdo จาก database.php จะใช้ได้ในไฟล์นี้
require_once __DIR__ . '/config/database.php';

// =============================================
// ดึงข้อมูลสถิติจาก Database
// =============================================
// query() = ส่งคำสั่ง SQL ไปยัง Database
// fetchColumn() = ดึงค่าคอลัมน์แรกของผลลัพธ์ (เอาแค่ตัวเลข)
//
// เปรียบเทียบกับ JavaScript:
// const result = await connection.query('SELECT COUNT(*) FROM students');
// const count = result[0][0];
//
// PHP สั้นกว่า:
// $count = $pdo->query('SELECT COUNT(*)...')->fetchColumn();

$studentCount = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$courseCount = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$enrollmentCount = $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();

// =============================================
// แสดงผล HTML
// =============================================
// require_once header.php -> สร้าง <html><head><body>
// require_once navbar.php -> สร้างแถบเมนู
// จากนั้นเขียน HTML ของหน้านี้
// require_once footer.php -> ปิด </body></html>

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- เนื้อหาของหน้า Dashboard -->
<div class="main-content">

    <!-- หัวข้อหน้า -->
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fa-solid fa-chart-line"></i></span>
            Dashboard
        </h1>
    </div>

    <!-- สถิติ 3 กล่อง -->
    <div class="stats-grid">

        <!-- กล่องนักศึกษา (คลิกได้) -->
        <a href="pages/students.php" class="stat-card">
            <div class="stat-icon students"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="stat-info">
                <h3>นักศึกษาทั้งหมด</h3>
                <div class="stat-number"><?= $studentCount ?></div>
            </div>
        </a>

        <!-- กล่องรายวิชา (คลิกได้) -->
        <a href="pages/courses.php" class="stat-card">
            <div class="stat-icon courses"><i class="fa-solid fa-book"></i></div>
            <div class="stat-info">
                <h3>รายวิชาทั้งหมด</h3>
                <div class="stat-number"><?= $courseCount ?></div>
            </div>
        </a>

        <!-- กล่องลงทะเบียน (คลิกได้) -->
        <a href="pages/enrollments.php" class="stat-card">
            <div class="stat-icon enrollments"><i class="fa-solid fa-clipboard-list"></i></div>
            <div class="stat-info">
                <h3>การลงทะเบียนทั้งหมด</h3>
                <div class="stat-number"><?= $enrollmentCount ?></div>
            </div>
        </a>

    </div>

    <!-- ตารางลงทะเบียนล่าสุด -->
    <div class="card">
        <h2 style="font-size: 1.15rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-clock-rotate-left"></i> การลงทะเบียนล่าสุด
        </h2>

        <?php
        // =============================================
        // ดึงข้อมูลลงทะเบียนล่าสุด 5 รายการ
        // =============================================
        // JOIN = เชื่อมตารางเข้าด้วยกัน
        // เรา JOIN 3 ตาราง: enrollments + students + courses
        // เพื่อดึงชื่อนักศึกษาและชื่อวิชา (แทนที่จะได้แค่ id)
        //
        // ORDER BY e.id DESC = เรียงจากใหม่สุดไปเก่าสุด
        // LIMIT 5 = เอาแค่ 5 รายการ
        $stmt = $pdo->query("
            SELECT e.*, s.name AS student_name, s.student_code, c.course_code, c.course_name
            FROM enrollments e
            JOIN students s ON e.student_id = s.id
            JOIN courses c ON e.course_id = c.id
            ORDER BY e.id DESC
            LIMIT 5
        ");

        // fetchAll() = ดึงผลลัพธ์ทั้งหมดเป็น array
        // เหมือน: const recentEnrollments = results.rows; ใน JS
        $recentEnrollments = $stmt->fetchAll();
        ?>

        <?php if (count($recentEnrollments) > 0): ?>
        <!--
            count() ใน PHP = .length ใน JavaScript
            if (count($arr) > 0) เหมือน if (arr.length > 0)
            
            สังเกต syntax: if (...): ... endif;
            นี่คือ "alternative syntax" ของ PHP สำหรับผสมกับ HTML
            อ่านง่ายกว่า if (...) { } เมื่อมี HTML อยู่ระหว่างกลาง
        -->
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>นักศึกษา</th>
                        <th>รายวิชา</th>
                        <th>วันที่ลงทะเบียน</th>
                        <th>เกรด</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentEnrollments as $row): ?>
                    <!--
                        foreach ใน PHP เหมือน for...of ใน JavaScript:
                        JS:  for (const row of recentEnrollments) { }
                        PHP: foreach ($recentEnrollments as $row):
                        
                        แต่ละ $row เป็น associative array (คล้าย Object ใน JS)
                        $row['student_name'] เหมือน row.student_name ใน JS
                    -->
                    <tr>
                        <td><?= htmlspecialchars($row['student_code'] . ' - ' . $row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['course_code'] . ' - ' . $row['course_name']) ?></td>
                        <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                        <td>
                            <?php if ($row['grade']): ?>
                                <?php
                                // =============================================
                                // กำหนดสีของ grade badge
                                // =============================================
                                // strtolower() = แปลงเป็นตัวพิมพ์เล็ก
                                // substr() = ตัดตัวอักษร (เอาตัวแรก)
                                // เหมือน: str.toLowerCase() และ str.charAt(0) ใน JS
                                $gradeClass = 'grade-none';
                                $firstChar = strtolower(substr($row['grade'], 0, 1));
                                if ($firstChar === 'a') $gradeClass = 'grade-a';
                                elseif ($firstChar === 'b') $gradeClass = 'grade-b';
                                elseif ($firstChar === 'c') $gradeClass = 'grade-c';
                                elseif ($firstChar === 'd') $gradeClass = 'grade-d';
                                elseif ($firstChar === 'f') $gradeClass = 'grade-f';
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
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-inbox"></i></div>
                <p>ยังไม่มีข้อมูลการลงทะเบียน</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

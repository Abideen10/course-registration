<?php
// =============================================
// Navbar: แถบเมนูนำทาง
// =============================================
// ใช้ PHP เพื่อตรวจสอบว่าอยู่หน้าไหน แล้ว highlight เมนูนั้น
//
// Concept: $_SERVER['SCRIPT_NAME']
// คือ path ของไฟล์ PHP ที่กำลังทำงานอยู่
// เช่น ถ้าเปิด http://localhost/course-registration/pages/students.php
// $_SERVER['SCRIPT_NAME'] จะได้ "/course-registration/pages/students.php"
//
// เปรียบเทียบกับ JavaScript:
// JS:  window.location.pathname
// PHP: $_SERVER['SCRIPT_NAME']
//
// basename() = ตัดเอาเฉพาะชื่อไฟล์ (ตัด path ออก)
// เช่น basename("/course-registration/pages/students.php") = "students.php"
// เหมือน: path.split('/').pop() ใน JS
$currentPage = basename($_SERVER['SCRIPT_NAME']);

// ดึงข้อมูล user ปัจจุบัน (ถ้า login แล้ว)
require_once __DIR__ . '/auth.php';
$currentUser = getCurrentUser();
?>
<nav class="navbar">
    <div class="navbar-container">
        <!-- Logo / ชื่อระบบ -->
        <a href="/course-registration/index.php" class="navbar-brand">
            <span class="brand-icon"><i class="fa-solid fa-graduation-cap"></i></span>
            <span class="brand-text">University Registration</span>
        </a>

        <!-- เมนู -->
        <ul class="navbar-menu">
            <li>
                <a href="/course-registration/index.php"
                    class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span>
                    Dashboard
                </a>
            </li>

            <?php if (isAdmin()): ?>
            <!-- เมนูจัดการนักศึกษา (Admin เท่านั้น) -->
            <li>
                <a href="/course-registration/pages/students.php"
                    class="nav-link <?= $currentPage === 'students.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-user-graduate"></i></span>
                    Students
                </a>
            </li>
            <?php endif; ?>

            <li>
                <a href="/course-registration/pages/courses.php"
                    class="nav-link <?= $currentPage === 'courses.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-book"></i></span>
                    Courses
                </a>
            </li>
            <li>
                <a href="/course-registration/pages/enrollments.php"
                    class="nav-link <?= $currentPage === 'enrollments.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span>
                    Enrollments
                </a>
            <li>
                <a href="/course-registration/pages/transcript.php"
                    class="nav-link <?= $currentPage === 'transcript.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                    Transcript
                </a>
            </li>
            <li>
                <a href="/course-registration/pages/course_summary.php"
                    class="nav-link <?= $currentPage === 'course_summary.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span>
                    Course Summary
                </a>
            </li>
            <li>
                <a href="/course-registration/pages/student_search.php"
                    class="nav-link <?= $currentPage === 'student_search.php' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                    Search
                </a>
            </li>
        </ul>

        <!-- User Info & Logout -->
        <?php if ($currentUser): ?>
        <div class="navbar-user">
            <div class="user-info">
                <span class="user-name"><?= htmlspecialchars($currentUser['username']) ?></span>
                <span class="user-role-badge role-<?= $currentUser['role'] ?>">
                    <?= $currentUser['role'] === 'admin' ? 'Admin' : 'Student' ?>
                </span>
            </div>
            <a href="/course-registration/actions/auth_logout.php" class="btn-logout" title="ออกจากระบบ">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</nav>
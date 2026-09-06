<?php
// =============================================
// auth_logout.php — ประมวลผล Logout
// =============================================
// ทำลาย session ทั้งหมด → redirect ไปหน้า login
//
// เปรียบเทียบ Express.js:
// req.session.destroy(() => res.redirect('/login'));

session_start();

// ล้างข้อมูลใน $_SESSION ทั้งหมด
$_SESSION = [];

// ทำลาย session
session_destroy();

// Redirect ไปหน้า Login
header('Location: /course-registration/pages/login.php');
exit;

<?php
// =============================================
// auth_login.php — ประมวลผล Login
// =============================================
// รับข้อมูลจากฟอร์ม login.php ด้วย $_POST
// ตรวจสอบ username/password กับ Database
// ถ้าถูกต้อง → สร้าง session → redirect ไป dashboard
// ถ้าผิด → redirect กลับ login.php พร้อม error message
//
// Concept สำคัญ:
// - password_verify() = ตรวจ password กับ hash ที่เก็บไว้
//   เหมือน bcrypt.compare() ใน Node.js
// - $_SESSION = เก็บข้อมูลฝั่ง server (อยู่ข้ามหน้าได้)
//   เหมือน req.session ใน Express.js

// เริ่ม session
session_start();

// เชื่อมต่อ Database
require_once __DIR__ . '/../config/database.php';

// =============================================
// ตรวจสอบว่าเป็น POST request หรือไม่
// =============================================
// $_SERVER['REQUEST_METHOD'] = method ของ HTTP request
// เหมือน req.method ใน Express.js
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /course-registration/pages/login.php');
    exit;
}

// =============================================
// รับข้อมูลจากฟอร์ม
// =============================================
// trim() = ตัดช่องว่างหน้า-หลัง (เหมือน .trim() ใน JS)
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

// =============================================
// Validation: ตรวจสอบว่ากรอกครบหรือไม่
// =============================================
if (empty($username) || empty($password)) {
    header('Location: /course-registration/pages/login.php?error=กรุณากรอก username และ password');
    exit;
}

// =============================================
// ค้นหา user จาก Database
// =============================================
// prepare() + execute() = Prepared Statement (ป้องกัน SQL Injection)
// เหมือนการใช้ parameterized query ใน Node.js:
//   connection.query('SELECT * FROM users WHERE username = ?', [username])
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

// =============================================
// ตรวจสอบ password
// =============================================
// password_verify(plain, hash) = ตรวจว่า password ตรงกับ hash ที่เก็บไว้
// return true/false
//
// ทำไมไม่ hash แล้วเปรียบเทียบตรงๆ?
// เพราะ bcrypt สร้าง hash ที่ต่างกันทุกครั้ง (มี salt)
// ต้องใช้ password_verify() เท่านั้น
if (!$user || !password_verify($password, $user['password'])) {
    header('Location: /course-registration/pages/login.php?error=username หรือ password ไม่ถูกต้อง');
    exit;
}

// =============================================
// Login สำเร็จ → สร้าง Session
// =============================================
// เก็บข้อมูล user ลง $_SESSION
// ข้อมูลนี้จะอยู่ตลอดจนกว่าจะ logout หรือปิด browser
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['name'] = $user['name'];

// Redirect ไป Dashboard
header('Location: /course-registration/index.php');
exit;

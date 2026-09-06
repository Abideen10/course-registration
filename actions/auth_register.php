<?php
// =============================================
// auth_register.php — ประมวลผล Register
// =============================================
// รับข้อมูลจากฟอร์ม register.php
// Validate → hash password → INSERT เข้า users
// สมัครแล้ว role จะเป็น 'student' เสมอ

session_start();
require_once __DIR__ . '/../config/database.php';

// ตรวจสอบว่าเป็น POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /course-registration/pages/register.php');
    exit;
}

// =============================================
// รับข้อมูลจากฟอร์ม
// =============================================
$username        = trim($_POST['username'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// =============================================
// Validation
// =============================================

// 1. ตรวจว่ากรอกครบ
if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    header('Location: /course-registration/pages/register.php?error=กรุณากรอกข้อมูลให้ครบทุกช่อง');
    exit;
}

// 2. ตรวจว่า password ตรงกัน
if ($password !== $confirmPassword) {
    header('Location: /course-registration/pages/register.php?error=รหัสผ่านไม่ตรงกัน');
    exit;
}

// 3. ตรวจความยาว password
if (strlen($password) < 6) {
    header('Location: /course-registration/pages/register.php?error=รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
    exit;
}

// 4. ตรวจรูปแบบ email
// filter_var() = ตรวจ format ของ email
// เหมือน regex.test(email) ใน JS แต่ PHP มี built-in function
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /course-registration/pages/register.php?error=รูปแบบ email ไม่ถูกต้อง');
    exit;
}

// 5. ตรวจว่า username ซ้ำหรือไม่
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
$stmt->execute([':username' => $username]);
if ($stmt->fetch()) {
    header('Location: /course-registration/pages/register.php?error=username นี้ถูกใช้แล้ว');
    exit;
}

// 6. ตรวจว่า email ซ้ำหรือไม่
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    header('Location: /course-registration/pages/register.php?error=email นี้ถูกใช้แล้ว');
    exit;
}

// =============================================
// INSERT ข้อมูลลง Database
// =============================================
try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO users (username, email, password, role)
        VALUES (:username, :email, :password, 'student')
    ");
    $stmt->execute([
        ':username' => $username,
        ':email'    => $email,
        ':password' => $hashedPassword,
    ]);

    // สำเร็จ → redirect ไป login พร้อมข้อความ
    header('Location: /course-registration/pages/login.php?success=สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
    exit;

} catch (PDOException $e) {
    header('Location: /course-registration/pages/register.php?error=เกิดข้อผิดพลาด กรุณาลองใหม่');
    exit;
}

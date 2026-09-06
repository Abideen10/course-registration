<?php
// =============================================
// Auth Helper: ฟังก์ชันจัดการ Authentication
// =============================================
// ไฟล์นี้เก็บฟังก์ชันที่ใช้ร่วมกันทุกหน้า
// ทุกหน้าที่ต้องการ auth ให้ require_once ไฟล์นี้
//
// เปรียบเทียบกับ JavaScript:
// ใน Express.js จะใช้ middleware เช่น:
//   app.use(requireAuth);
// ใน PHP เราใช้ require_once + เรียกฟังก์ชันแทน

// เริ่ม session ถ้ายังไม่ได้เริ่ม
// session_status() ตรวจสอบว่า session เริ่มแล้วหรือยัง
// PHP_SESSION_NONE = ยังไม่ได้เริ่ม
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ตรวจสอบว่า user login แล้วหรือยัง
 * 
 * @return bool true ถ้า login แล้ว
 * 
 * เปรียบเทียบ JS:
 * const isLoggedIn = () => !!req.session.userId;
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * ตรวจสอบว่า user เป็น admin หรือไม่
 * 
 * @return bool true ถ้าเป็น admin
 */
function isAdmin(): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * ดึงข้อมูล user ที่ login อยู่
 * 
 * @return array|null ข้อมูล user หรือ null ถ้ายังไม่ login
 * 
 * เปรียบเทียบ JS:
 * const getCurrentUser = () => req.session.user || null;
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email'    => $_SESSION['user_email'],
        'role'     => $_SESSION['user_role'],
    ];
}

/**
 * บังคับให้ login ก่อนเข้าหน้านี้
 * ถ้ายังไม่ login จะ redirect ไปหน้า login
 * 
 * เปรียบเทียบ Express.js middleware:
 * const requireAuth = (req, res, next) => {
 *     if (!req.session.userId) return res.redirect('/login');
 *     next();
 * };
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /course-registration/pages/login.php');
        exit;
    }
}

/**
 * บังคับให้เป็น admin เท่านั้น
 * ถ้าไม่ใช่ admin จะ redirect กลับหน้า dashboard
 */
function requireAdmin(): void {
    requireLogin(); // ต้อง login ก่อน
    if (!isAdmin()) {
        header('Location: /course-registration/index.php?error=ไม่มีสิทธิ์เข้าถึงหน้านี้');
        exit;
    }
}

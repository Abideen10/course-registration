-- =============================================
-- Table: users (ตารางผู้ใช้งาน)
-- =============================================
-- ตารางนี้เก็บข้อมูลผู้ใช้สำหรับระบบ Login/Register
-- มี 2 role: admin (ผู้ดูแลระบบ) และ student (นักศึกษา)
--
-- password จะถูก hash ด้วย PHP password_hash() ก่อนเก็บ
-- จึงต้องใช้ VARCHAR(255) เพราะ hash string มีความยาวประมาณ 60 ตัวอักษร

USE course_registration;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

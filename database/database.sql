-- =============================================
-- Course Registration System
-- Database: course_registration
-- =============================================

-- สร้าง Database (ถ้ายังไม่มี)
CREATE DATABASE IF NOT EXISTS course_registration
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE course_registration;

-- =============================================
-- 1. Table: users (รวมข้อมูลผู้ใช้และนักศึกษาไว้ด้วยกัน)
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    student_code VARCHAR(20) UNIQUE DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- 2. Table: courses (ตารางรายวิชา)
-- =============================================
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    teacher VARCHAR(100) NOT NULL
);

-- =============================================
-- 3. Table: enrollments (ตารางการลงทะเบียนเรียน)
-- =============================================
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    grade VARCHAR(2) DEFAULT NULL,

    -- Foreign Key: ลบ user หรือ course จะลบการลงทะเบียนนั้นด้วย
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

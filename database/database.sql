-- =============================================
-- Course Registration System
-- Database: course_registration
-- =============================================

-- สร้าง Database (ถ้ายังไม่มี)
CREATE DATABASE IF NOT EXISTS course_registration
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- เลือกใช้ Database นี้
USE course_registration;

-- =============================================
-- Table: students (ตารางนักศึกษา)
-- =============================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    department VARCHAR(100) NOT NULL
);

-- =============================================
-- Table: courses (ตารางรายวิชา)
-- =============================================
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    teacher VARCHAR(100) NOT NULL
);

-- =============================================
-- Table: enrollments (ตารางลงทะเบียนเรียน)
-- =============================================
-- enrollments เชื่อมระหว่าง students กับ courses
-- student_id -> อ้างอิงไปที่ students.id
-- course_id  -> อ้างอิงไปที่ courses.id
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    grade VARCHAR(2) DEFAULT NULL,

    -- Foreign Key: ถ้า student ถูกลบ -> enrollment ของคนนั้นจะถูกลบด้วย
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,

    -- Foreign Key: ถ้า course ถูกลบ -> enrollment ของวิชานั้นจะถูกลบด้วย
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- =============================================
-- Sample Data: ข้อมูลตัวอย่าง
-- =============================================

-- นักศึกษาตัวอย่าง
INSERT INTO students (student_code, name, email, department) VALUES
('65001', 'สมชาย ใจดี', 'somchai@university.ac.th', 'วิทยาการคอมพิวเตอร์'),
('65002', 'สมหญิง รักเรียน', 'somying@university.ac.th', 'เทคโนโลยีสารสนเทศ'),
('65003', 'วิชัย เก่งมาก', 'wichai@university.ac.th', 'วิศวกรรมซอฟต์แวร์');

-- รายวิชาตัวอย่าง
INSERT INTO courses (course_code, course_name, credits, teacher) VALUES
('CS101', 'การเขียนโปรแกรมเบื้องต้น', 3, 'ผศ.ดร.สมศักดิ์'),
('CS102', 'โครงสร้างข้อมูล', 3, 'อ.วิภาวดี'),
('IT201', 'ฐานข้อมูลเบื้องต้น', 3, 'รศ.ดร.ประภาส');

-- ลงทะเบียนตัวอย่าง
INSERT INTO enrollments (student_id, course_id, enrollment_date, grade) VALUES
(1, 1, '2026-09-01', 'A'),
(1, 2, '2026-09-01', 'B+'),
(2, 1, '2026-09-02', NULL),
(3, 3, '2026-09-02', NULL);

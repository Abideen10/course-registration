<?php
// =============================================
// student_create.php — เพิ่มนักศึกษาใหม่ (Create)
// =============================================
// ไฟล์นี้ทำหน้าที่:
// 1. รับข้อมูลจาก HTML Form (ที่ส่งมาด้วย method="POST")
// 2. Validate ข้อมูล
// 3. บันทึกลง Database
// 4. Redirect กลับไปหน้า students.php

require_once __DIR__ . '/../config/database.php';

// =============================================
// ตรวจสอบว่าเป็น POST request หรือไม่
// =============================================
// $_SERVER['REQUEST_METHOD'] บอกว่า request นี้เป็น GET หรือ POST
// เหมือนการเช็ค req.method ใน Express.js:
// JS:  if (req.method === 'POST') { ... }
// PHP: if ($_SERVER['REQUEST_METHOD'] === 'POST') { ... }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =============================================
    // รับข้อมูลจาก Form
    // =============================================
    // $_POST คือ array ที่เก็บข้อมูลที่ส่งมาจาก HTML Form (method="POST")
    // เปรียบเทียบกับ JavaScript:
    // JS:  const name = req.body.name;         (Express.js)
    // PHP: $name = $_POST['name'];
    //
    // trim() = ตัดช่องว่างหัวท้าย (เหมือน .trim() ใน JS)
    $student_code = trim($_POST['student_code']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);

    // =============================================
    // Validate ข้อมูล
    // =============================================
    // empty() = ตรวจว่าค่าว่างหรือไม่
    // เหมือน: if (!value || value.trim() === '') ใน JS
    if (empty($student_code) || empty($name) || empty($email) || empty($department)) {
        // header('Location: ...') = redirect ไปหน้าอื่น
        // เหมือน: res.redirect('/students?error=...') ใน Express.js
        // urlencode() = เข้ารหัสข้อความภาษาไทยให้ใส่ใน URL ได้
        header('Location: ../pages/students.php?error=' . urlencode('กรุณากรอกข้อมูลให้ครบทุกช่อง'));
        exit;  // หยุดทำงาน (ไม่ทำบรรทัดด้านล่างต่อ)
    }

    // =============================================
    // บันทึกลง Database ด้วย Prepared Statement
    // =============================================
    try {
        // prepare() = เตรียมคำสั่ง SQL โดยใช้ ? เป็น placeholder
        // execute() = ใส่ค่าจริงเข้าไปแทน ? (เรียงตามลำดับ)
        //
        // ทำไมต้องใช้ Prepared Statement?
        // ถ้าผู้ใช้พิมพ์ชื่อว่า: Robert'; DROP TABLE students; --
        // แบบไม่ปลอดภัย: SQL จะถูกแทรกคำสั่งลบตาราง (SQL Injection!)
        // แบบ Prepared Statement: Database จะถือว่าทั้งหมดเป็น "ข้อมูล" ไม่ใช่ "คำสั่ง"
        $stmt = $pdo->prepare("INSERT INTO users (student_code, name, email, department) VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_code, $name, $email, $department]);

        header('Location: ../pages/students.php?success=' . urlencode('เพิ่มนักศึกษาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        // ถ้า error (เช่น student_code ซ้ำ เพราะ UNIQUE constraint)
        header('Location: ../pages/students.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

// ถ้าไม่ใช่ POST request (เช่น เข้ามาด้วย GET โดยตรง) -> redirect กลับ
header('Location: ../pages/students.php');
exit;

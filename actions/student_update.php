<?php
// =============================================
// student_update.php — แก้ไขข้อมูลนักศึกษา (Update)
// =============================================
// Flow การทำงาน:
// 1. User กด "แก้ไข" ที่หน้า students.php
// 2. students.php แสดง Edit Modal พร้อมข้อมูลเดิม
// 3. User แก้ไขข้อมูลแล้วกด "อัพเดต"
// 4. Form ส่ง POST มาที่ไฟล์นี้
// 5. ไฟล์นี้ UPDATE ข้อมูลใน Database
// 6. Redirect กลับ students.php

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // รับข้อมูลจาก Form
    // (int) = type casting — แปลงค่าเป็นตัวเลข
    // เหมือน parseInt() ใน JS: const id = parseInt(req.body.id)
    $id = (int) $_POST['id'];
    $student_code = trim($_POST['student_code']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);

    // Validate
    if (empty($student_code) || empty($name) || empty($email) || empty($department)) {
        header('Location: ../pages/students.php?error=' . urlencode('กรุณากรอกข้อมูลให้ครบทุกช่อง'));
        exit;
    }

    try {
        // UPDATE = แก้ไขข้อมูลที่มีอยู่แล้ว
        // WHERE id = ? = ระบุว่าจะแก้ไขแถวไหน (ถ้าไม่มี WHERE จะแก้ทุกแถว!)
        $stmt = $pdo->prepare("UPDATE users SET student_code = ?, name = ?, email = ?, department = ? WHERE id = ?");
        $stmt->execute([$student_code, $name, $email, $department, $id]);

        header('Location: ../pages/students.php?success=' . urlencode('แก้ไขข้อมูลนักศึกษาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/students.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/students.php');
exit;

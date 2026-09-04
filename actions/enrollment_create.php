<?php
// =============================================
// enrollment_create.php — ลงทะเบียนเรียน (Create)
// =============================================
// ต่างจาก student_create ตรงที่:
// 1. ต้องตรวจว่า student_id และ course_id มีอยู่จริงใน Database
// 2. ต้องตรวจว่าไม่ลงทะเบียนซ้ำ (นักศึกษาคนเดียวกัน + วิชาเดียวกัน)

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_id = (int) $_POST['student_id'];
    $course_id = (int) $_POST['course_id'];
    $enrollment_date = trim($_POST['enrollment_date']);

    // =============================================
    // Validate: ข้อมูลครบหรือไม่
    // =============================================
    if (!$student_id || !$course_id || empty($enrollment_date)) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('กรุณาเลือกนักศึกษาและรายวิชา'));
        exit;
    }

    // =============================================
    // ตรวจสอบว่าลงทะเบียนซ้ำหรือไม่
    // =============================================
    // ดึงจำนวน enrollment ที่มี student_id + course_id เดียวกัน
    // ถ้า > 0 แสดงว่าลงทะเบียนซ้ำ
    //
    // fetchColumn() ดึงค่าคอลัมน์แรก (ในที่นี้คือ COUNT)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    $exists = $stmt->fetchColumn();

    if ($exists > 0) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('นักศึกษาคนนี้ลงทะเบียนวิชานี้แล้ว'));
        exit;
    }

    try {
        // grade ไม่ต้องใส่ตอนลงทะเบียน (จะเป็น NULL = ยังไม่มีเกรด)
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (?, ?, ?)");
        $stmt->execute([$student_id, $course_id, $enrollment_date]);

        header('Location: ../pages/enrollments.php?success=' . urlencode('ลงทะเบียนเรียนสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/enrollments.php');
exit;

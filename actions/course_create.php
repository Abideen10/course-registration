<?php
// =============================================
// course_create.php — เพิ่มรายวิชาใหม่ (Create)
// =============================================
// Pattern เดียวกับ student_create.php:
// รับ POST -> Validate -> INSERT -> Redirect

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $credits = (int) $_POST['credits'];
    $teacher = trim($_POST['teacher']);

    // Validate ข้อมูล
    if (empty($course_code) || empty($course_name) || empty($teacher)) {
        header('Location: ../pages/courses.php?error=' . urlencode('กรุณากรอกข้อมูลให้ครบทุกช่อง'));
        exit;
    }

    // Validate หน่วยกิต (ตรวจสอบฝั่ง server ด้วย)
    // ถึงแม้ HTML จะมี min="1" max="6" แต่ user สามารถแก้ HTML ได้
    // ดังนั้นต้องตรวจที่ PHP เสมอ!
    if ($credits < 1 || $credits > 6) {
        header('Location: ../pages/courses.php?error=' . urlencode('หน่วยกิตต้องอยู่ระหว่าง 1-6'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, credits, teacher) VALUES (?, ?, ?, ?)");
        $stmt->execute([$course_code, $course_name, $credits, $teacher]);

        header('Location: ../pages/courses.php?success=' . urlencode('เพิ่มรายวิชาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/courses.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/courses.php');
exit;

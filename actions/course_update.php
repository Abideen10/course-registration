<?php
// =============================================
// course_update.php — แก้ไขรายวิชา (Update)
// =============================================

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $credits = (int) $_POST['credits'];
    $teacher = trim($_POST['teacher']);

    // Validate
    if (empty($course_code) || empty($course_name) || empty($teacher)) {
        header('Location: ../pages/courses.php?error=' . urlencode('กรุณากรอกข้อมูลให้ครบทุกช่อง'));
        exit;
    }

    if ($credits < 1 || $credits > 6) {
        header('Location: ../pages/courses.php?error=' . urlencode('หน่วยกิตต้องอยู่ระหว่าง 1-6'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, credits = ?, teacher = ? WHERE id = ?");
        $stmt->execute([$course_code, $course_name, $credits, $teacher, $id]);

        header('Location: ../pages/courses.php?success=' . urlencode('แก้ไขรายวิชาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/courses.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/courses.php');
exit;

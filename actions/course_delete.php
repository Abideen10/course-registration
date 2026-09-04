<?php
// =============================================
// course_delete.php — ลบรายวิชา (Delete)
// =============================================
// เหมือน student_delete.php
// ON DELETE CASCADE จะลบ enrollment ที่เกี่ยวข้องอัตโนมัติ

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    if (!$id) {
        header('Location: ../pages/courses.php?error=' . urlencode('ไม่พบข้อมูลรายวิชา'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: ../pages/courses.php?success=' . urlencode('ลบรายวิชาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/courses.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/courses.php');
exit;

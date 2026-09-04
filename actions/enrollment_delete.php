<?php
// =============================================
// enrollment_delete.php — ยกเลิกการลงทะเบียน (Delete)
// =============================================

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    if (!$id) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('ไม่พบข้อมูลการลงทะเบียน'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: ../pages/enrollments.php?success=' . urlencode('ยกเลิกการลงทะเบียนสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/enrollments.php');
exit;

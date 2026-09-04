<?php
// =============================================
// student_delete.php — ลบนักศึกษา (Delete)
// =============================================
// ข้อควรระวัง:
// เนื่องจากเรา set ON DELETE CASCADE ใน database.sql
// เมื่อลบ student -> enrollment ที่เกี่ยวข้องจะถูกลบอัตโนมัติ

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    // ตรวจสอบว่า id ถูกต้อง
    // ใน PHP: 0 ถือเป็น false, ตัวเลขอื่น = true (เหมือน JS)
    if (!$id) {
        header('Location: ../pages/students.php?error=' . urlencode('ไม่พบข้อมูลนักศึกษา'));
        exit;
    }

    try {
        // DELETE FROM = ลบแถวออกจากตาราง
        // WHERE id = ? = ระบุว่าจะลบแถวไหน
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);

        header('Location: ../pages/students.php?success=' . urlencode('ลบนักศึกษาสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/students.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/students.php');
exit;

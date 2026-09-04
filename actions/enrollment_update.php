<?php
// =============================================
// enrollment_update.php — แก้ไขเกรด (Update)
// =============================================
// แก้ไขเฉพาะ grade ของ enrollment
// ไม่สามารถเปลี่ยนนักศึกษาหรือรายวิชาได้

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];

    // =============================================
    // จัดการค่า grade ที่อาจเป็นค่าว่าง
    // =============================================
    // ถ้า user เลือก "ยังไม่มี" (value="") -> เก็บเป็น NULL ใน Database
    // trim() ก่อน แล้วเช็คว่าว่างหรือไม่
    //
    // ternary operator ใน PHP ทำงานเหมือน JS:
    // JS:  const grade = value !== '' ? value : null;
    // PHP: $grade = $rawGrade !== '' ? $rawGrade : null;
    $rawGrade = trim($_POST['grade']);
    $grade = $rawGrade !== '' ? $rawGrade : null;

    if (!$id) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('ไม่พบข้อมูลการลงทะเบียน'));
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE enrollments SET grade = ? WHERE id = ?");
        $stmt->execute([$grade, $id]);

        header('Location: ../pages/enrollments.php?success=' . urlencode('อัพเดตเกรดสำเร็จ'));
        exit;

    } catch (PDOException $e) {
        header('Location: ../pages/enrollments.php?error=' . urlencode('เกิดข้อผิดพลาด: ' . $e->getMessage()));
        exit;
    }
}

header('Location: ../pages/enrollments.php');
exit;

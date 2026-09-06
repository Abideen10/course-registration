<?php
// =============================================
// student_search.php — ค้นหาข้อมูลนักศึกษา (Search)
// =============================================

require_once __DIR__ . '/../config/database.php';

// 1. รับค่าคำค้นหาจาก URL ผ่าน $_GET
// ถ้าผู้ใช้ยังไม่ได้พิมพ์อะไร ให้ $keyword เป็นข้อความว่างเปล่า ''
$keyword = trim($_GET['keyword'] ?? '');

// 2. ดึงข้อมูลตามเงื่อนไขการค้นหา
if ($keyword !== '') {
    // กรณีมีคำค้นหา: ใช้ LIKE ร่วมกับเครื่องหมาย % เพื่อค้นหาคำใกล้เคียง
    // ค้นหาพร้อมกันจาก: รหัสนักศึกษา, ชื่อ-นามสกุล, หรือสาขาวิชา
    $sql = "
        SELECT * FROM students 
        WHERE student_code LIKE ? 
           OR name LIKE ? 
           OR department LIKE ?
        ORDER BY student_code ASC
    ";
    $stmt = $pdo->prepare($sql);

    // ใส่เครื่องหมาย % ประกบหน้า-หลังคำค้นหา
    // เช่น ถ้าพิมพ์ "สม" จะกลายเป็น "%สม%" (ข้างหน้าหรือข้างหลังจะมีตัวอะไรก็ได้)
    $searchTerm = "%" . $keyword . "%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $students = $stmt->fetchAll();
} else {
    // กรณีเปิดหน้าเว็บมาครั้งแรก (ยังไม่ได้กดค้นหา): แสดงรายชื่อนักศึกษาทั้งหมด
    $stmt = $pdo->query("SELECT * FROM students ORDER BY student_code ASC");
    $students = $stmt->fetchAll();
}

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="main-content">
    <div class="card">
        <h2 style="font-size: 1.15rem; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-magnifying-glass"></i> ค้นหาข้อมูลนักศึกษา
        </h2>

        <!-- ฟอร์มค้นหา: ใช้ method="GET" เพื่อให้คำค้นหาติดไปกับ URL -->
        <form method="GET" action="student_search.php" style="display: flex; gap: 10px; margin-bottom: 25px;">
            <input type="text" name="keyword" class="form-control" placeholder="พิมพ์ชื่อ, รหัสนักศึกษา หรือสาขาวิชา..."
                value="<?= htmlspecialchars($keyword) ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
            </button>
            <?php if ($keyword !== ''): ?>
                <a href="student_search.php" class="btn btn-secondary">ล้างการค้นหา</a>
            <?php endif; ?>
        </form>

        <!-- แสดงข้อความสรุปผลการค้นหา -->
        <?php if ($keyword !== ''): ?>
            <p style="margin-bottom: 15px; color: #64748b;">
                ผลการค้นหาสำหรับ: <strong>"<?= htmlspecialchars($keyword) ?>"</strong>
                พบทั้งหมด <?= count($students) ?> รายการ
            </p>
        <?php endif; ?>

        <!-- ตารางแสดงผลลัพธ์ -->
        <?php if (count($students) > 0): ?>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>สาขาวิชา</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_code']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['department']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- กรณีค้นหาแล้วไม่พบข้อมูล -->
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fa-solid fa-user-xmark"></i></div>
                <p>ไม่พบข้อมูลนักศึกษาที่ตรงกับคำว่า "<?= htmlspecialchars($keyword) ?>"</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
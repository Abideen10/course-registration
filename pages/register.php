<?php
// =============================================
// register.php — หน้า Register (สมัครสมาชิก)
// =============================================
// หน้านี้แสดงฟอร์มสมัครสมาชิก
// user ที่สมัครจะได้ role = 'student' เสมอ
// (admin สร้างผ่าน seed เท่านั้น)

require_once __DIR__ . '/../includes/auth.php';

// ถ้า login แล้ว ไม่ต้องแสดงหน้า register อีก
if (isLoggedIn()) {
    header('Location: /course-registration/index.php');
    exit;
}

// รับ message จาก URL
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก | Course Registration System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/course-registration/css/style.css?v=<?= time() ?>">
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <!-- Logo -->
        <div class="auth-logo">
            <div class="auth-logo-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1>สมัครสมาชิก</h1>
            <p>สร้างบัญชีเพื่อเข้าใช้งานระบบ</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form class="auth-form" action="/course-registration/actions/auth_register.php" method="POST">
            <div class="form-group">
                <label for="username">
                    <i class="fa-solid fa-user"></i> Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="กรอก username ที่ต้องการ"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fa-solid fa-envelope"></i> Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="กรอก email ของคุณ"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fa-solid fa-lock"></i> Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="กรอกรหัสผ่าน (อย่างน้อย 6 ตัว)"
                    required
                    minlength="6"
                >
            </div>

            <div class="form-group">
                <label for="confirm_password">
                    <i class="fa-solid fa-lock"></i> ยืนยันรหัสผ่าน
                </label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    class="form-control"
                    placeholder="กรอกรหัสผ่านอีกครั้ง"
                    required
                    minlength="6"
                >
            </div>

            <button type="submit" class="btn btn-primary auth-btn">
                <i class="fa-solid fa-user-plus"></i>
                สมัครสมาชิก
            </button>
        </form>

        <!-- Link to Login -->
        <div class="auth-footer">
            <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>
</div>

</body>
</html>

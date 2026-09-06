<?php
// =============================================
// login.php — หน้า Login (เข้าสู่ระบบ)
// =============================================
// หน้านี้แสดงฟอร์มให้ user กรอก username + password
// เมื่อกด submit จะส่งข้อมูลไป actions/auth_login.php
//
// ถ้า login แล้ว จะ redirect ไป dashboard ทันที

require_once __DIR__ . '/../includes/auth.php';

// ถ้า login แล้ว ไม่ต้องแสดงหน้า login อีก
if (isLoggedIn()) {
    header('Location: /course-registration/index.php');
    exit;
}

// รับ message จาก URL (query string)
// เช่น login.php?error=รหัสผ่านไม่ถูกต้อง
// เช่น login.php?success=สมัครสมาชิกสำเร็จ
$error   = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | Course Registration System</title>

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
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1>University Registration</h1>
            <p>เข้าสู่ระบบเพื่อจัดการข้อมูล</p>
        </div>

        <!-- Alert Messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form class="auth-form" action="/course-registration/actions/auth_login.php" method="POST">
            <div class="form-group">
                <label for="username">
                    <i class="fa-solid fa-user"></i> Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="กรอก username ของคุณ"
                    required
                    autofocus
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
                    placeholder="กรอกรหัสผ่าน"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary auth-btn">
                <i class="fa-solid fa-right-to-bracket"></i>
                เข้าสู่ระบบ
            </button>
        </form>

        <!-- Link to Register -->
        <div class="auth-footer">
            <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
        </div>
    </div>
</div>

</body>
</html>

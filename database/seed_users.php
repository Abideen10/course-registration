<?php
// =============================================
// Seed Users: เพิ่มข้อมูลผู้ใช้ตัวอย่าง
// =============================================
// รันไฟล์นี้ผ่าน browser 1 ครั้ง:
// http://localhost/course-registration/database/seed_users.php
//
// จะสร้าง:
// - Admin 1 คน (admin / password123)
// - Student 10 คน (student01-10 / student123)
//
// password_hash() ใช้ algorithm bcrypt เพื่อ hash password
// ไม่เก็บ password เป็น plain text เพื่อความปลอดภัย

require_once __DIR__ . '/../config/database.php';

// =============================================
// ข้อมูลผู้ใช้ที่จะ seed
// =============================================
$users = [
    // Admin
    [
        'username' => 'admin',
        'email'    => 'admin@university.ac.th',
        'password' => 'password123',
        'role'     => 'admin'
    ],
    // Students
    [
        'username' => 'student01',
        'email'    => 'somchai@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student02',
        'email'    => 'somying@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student03',
        'email'    => 'wichai@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student04',
        'email'    => 'natthapol@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student05',
        'email'    => 'pimlapas@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student06',
        'email'    => 'kittisak@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student07',
        'email'    => 'onnicha@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student08',
        'email'    => 'thanakrit@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student09',
        'email'    => 'piyathida@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
    [
        'username' => 'student10',
        'email'    => 'supawit@university.ac.th',
        'password' => 'student123',
        'role'     => 'student'
    ],
];

// =============================================
// INSERT ข้อมูลลง Database
// =============================================
$sql = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
$stmt = $pdo->prepare($sql);

$successCount = 0;
$skipCount = 0;

foreach ($users as $user) {
    try {
        // password_hash() = hash password ด้วย bcrypt
        // เหมือน bcrypt.hash(password, saltRounds) ใน Node.js
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            ':username' => $user['username'],
            ':email'    => $user['email'],
            ':password' => $hashedPassword,
            ':role'     => $user['role'],
        ]);
        $successCount++;
        echo "✅ เพิ่ม {$user['username']} ({$user['role']}) สำเร็จ<br>";

    } catch (PDOException $e) {
        // ถ้า username หรือ email ซ้ำ จะเข้า catch
        $skipCount++;
        echo "⚠️ ข้าม {$user['username']} - มีอยู่แล้ว<br>";
    }
}

echo "<br>==============================<br>";
echo "สรุป: เพิ่มสำเร็จ {$successCount} คน, ข้าม {$skipCount} คน<br>";
echo "<br><a href='/course-registration/pages/login.php'>ไปหน้า Login</a>";

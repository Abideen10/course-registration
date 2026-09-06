<?php
// =============================================
// Seed Data: เพิ่มข้อมูลเริ่มต้นทั้งหมดของระบบ
// =============================================
// รันไฟล์นี้ผ่าน browser:
// http://localhost/course-registration/database/seed.php
//
// จะสร้าง:
// 1. Users: Admin 1 คน + Student 10 คน (พร้อมรหัสผ่าน Bcrypt)
// 2. Courses: รายวิชาตัวอย่าง 5 วิชา
// 3. Enrollments: ข้อมูลการลงทะเบียนตัวอย่าง

require_once __DIR__ . '/../config/database.php';

echo "<h2>🌱 กำลังเริ่มต้น Seed ข้อมูล...</h2>";

// =============================================
// 1. SEED USERS (Admin 1 + Students 10)
// =============================================
$users = [
    // Admin
    [
        'username'     => 'admin',
        'email'        => 'admin@university.ac.th',
        'password'     => 'password123',
        'name'         => 'ผู้ดูแลระบบ',
        'student_code' => null,
        'department'   => null,
        'role'         => 'admin'
    ],
    // Students 10 คน
    [
        'username'     => 'student01',
        'email'        => 'somchai@university.ac.th',
        'password'     => 'student123',
        'name'         => 'สมชาย ใจดี',
        'student_code' => '65001',
        'department'   => 'วิทยาการคอมพิวเตอร์',
        'role'         => 'student'
    ],
    [
        'username'     => 'student02',
        'email'        => 'somying@university.ac.th',
        'password'     => 'student123',
        'name'         => 'สมหญิง รักเรียน',
        'student_code' => '65002',
        'department'   => 'เทคโนโลยีสารสนเทศ',
        'role'         => 'student'
    ],
    [
        'username'     => 'student03',
        'email'        => 'wichai@university.ac.th',
        'password'     => 'student123',
        'name'         => 'วิชัย เก่งมาก',
        'student_code' => '65003',
        'department'   => 'วิศวกรรมซอฟต์แวร์',
        'role'         => 'student'
    ],
    [
        'username'     => 'student04',
        'email'        => 'natthapol@university.ac.th',
        'password'     => 'student123',
        'name'         => 'ณัฐพล มั่นคง',
        'student_code' => '65004',
        'department'   => 'วิทยาการคอมพิวเตอร์',
        'role'         => 'student'
    ],
    [
        'username'     => 'student05',
        'email'        => 'pimlapas@university.ac.th',
        'password'     => 'student123',
        'name'         => 'พิมพ์ลภัส สดใส',
        'student_code' => '65005',
        'department'   => 'เทคโนโลยีสารสนเทศ',
        'role'         => 'student'
    ],
    [
        'username'     => 'student06',
        'email'        => 'kittisak@university.ac.th',
        'password'     => 'student123',
        'name'         => 'กิตติศักดิ์ พัฒนา',
        'student_code' => '65006',
        'department'   => 'วิศวกรรมคอมพิวเตอร์',
        'role'         => 'student'
    ],
    [
        'username'     => 'student07',
        'email'        => 'onnicha@university.ac.th',
        'password'     => 'student123',
        'name'         => 'อรณิชา รุ่งเรือง',
        'student_code' => '65007',
        'department'   => 'วิทยาการข้อมูล',
        'role'         => 'student'
    ],
    [
        'username'     => 'student08',
        'email'        => 'thanakrit@university.ac.th',
        'password'     => 'student123',
        'name'         => 'ธนกฤต ชาญฉลาด',
        'student_code' => '65008',
        'department'   => 'วิศวกรรมซอฟต์แวร์',
        'role'         => 'student'
    ],
    [
        'username'     => 'student09',
        'email'        => 'piyathida@university.ac.th',
        'password'     => 'student123',
        'name'         => 'ปิยธิดา สุขสมบูรณ์',
        'student_code' => '65009',
        'department'   => 'เทคโนโลยีสารสนเทศ',
        'role'         => 'student'
    ],
    [
        'username'     => 'student10',
        'email'        => 'supawit@university.ac.th',
        'password'     => 'student123',
        'name'         => 'ศุภวิชญ์ ก้องเกียรติ',
        'student_code' => '65010',
        'department'   => 'วิทยาการคอมพิวเตอร์',
        'role'         => 'student'
    ],
];

$stmtUser = $pdo->prepare("
    INSERT INTO users (username, email, password, name, student_code, department, role)
    VALUES (:username, :email, :password, :name, :student_code, :department, :role)
");

$userCount = 0;
foreach ($users as $u) {
    try {
        $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmtUser->execute([
            ':username'     => $u['username'],
            ':email'        => $u['email'],
            ':password'     => $hashed,
            ':name'         => $u['name'],
            ':student_code' => $u['student_code'],
            ':department'   => $u['department'],
            ':role'         => $u['role'],
        ]);
        $userCount++;
    } catch (PDOException $e) {
        // ข้ามถ้ามีแล้ว
    }
}
echo "✅ บันทึกข้อมูลผู้ใช้ (Users) สำเร็จ: {$userCount} คน<br>";

// =============================================
// 2. SEED COURSES (รายวิชา 5 วิชา)
// =============================================
$courses = [
    ['course_code' => 'CS101', 'course_name' => 'การเขียนโปรแกรมคอมพิวเตอร์เบื้องต้น', 'credits' => 3, 'teacher' => 'ผศ.ดร.สมศักดิ์'],
    ['course_code' => 'CS102', 'course_name' => 'โครงสร้างข้อมูลและอัลกอริทึม',         'credits' => 3, 'teacher' => 'อ.วิภาวดี'],
    ['course_code' => 'IT201', 'course_name' => 'ระบบจัดการฐานข้อมูล',                 'credits' => 3, 'teacher' => 'รศ.ดร.ประภาส'],
    ['course_code' => 'SE202', 'course_name' => 'การวิเคราะห์และออกแบบระบบซอฟต์แวร์', 'credits' => 3, 'teacher' => 'ผศ.กมลวรรณ'],
    ['course_code' => 'GEN101', 'course_name' => 'ภาษาอังกฤษเพื่อการสื่อสาร',          'credits' => 2, 'teacher' => 'ดร.จอห์น สมิธ'],
];

$stmtCourse = $pdo->prepare("
    INSERT INTO courses (course_code, course_name, credits, teacher)
    VALUES (:course_code, :course_name, :credits, :teacher)
");

$courseCount = 0;
foreach ($courses as $c) {
    try {
        $stmtCourse->execute([
            ':course_code' => $c['course_code'],
            ':course_name' => $c['course_name'],
            ':credits'     => $c['credits'],
            ':teacher'     => $c['teacher'],
        ]);
        $courseCount++;
    } catch (PDOException $e) {
        // ข้ามถ้ามีแล้ว
    }
}
echo "✅ บันทึกรายวิชา (Courses) สำเร็จ: {$courseCount} วิชา<br>";

// =============================================
// 3. SEED ENROLLMENTS (การลงทะเบียนตัวอย่าง)
// =============================================
// ดึง id ของ users ที่เป็น student
$studentIds = $pdo->query("SELECT id FROM users WHERE role = 'student' ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);
$courseIds = $pdo->query("SELECT id FROM courses ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);

$enrollCount = 0;
if (!empty($studentIds) && !empty($courseIds)) {
    $stmtEnroll = $pdo->prepare("
        INSERT INTO enrollments (user_id, course_id, enrollment_date, grade)
        VALUES (:user_id, :course_id, :enrollment_date, :grade)
    ");

    $sampleEnrollments = [
        ['user_id' => $studentIds[0], 'course_id' => $courseIds[0], 'enrollment_date' => '2026-09-01', 'grade' => 'A'],
        ['user_id' => $studentIds[0], 'course_id' => $courseIds[1], 'enrollment_date' => '2026-09-01', 'grade' => 'B+'],
        ['user_id' => $studentIds[0], 'course_id' => $courseIds[2], 'enrollment_date' => '2026-09-01', 'grade' => 'A'],
        ['user_id' => $studentIds[1], 'course_id' => $courseIds[0], 'enrollment_date' => '2026-09-02', 'grade' => 'B'],
        ['user_id' => $studentIds[1], 'course_id' => $courseIds[2], 'enrollment_date' => '2026-09-02', 'grade' => NULL],
        ['user_id' => $studentIds[2], 'course_id' => $courseIds[1], 'enrollment_date' => '2026-09-03', 'grade' => 'C+'],
        ['user_id' => $studentIds[2], 'course_id' => $courseIds[3], 'enrollment_date' => '2026-09-03', 'grade' => NULL],
        ['user_id' => $studentIds[3], 'course_id' => $courseIds[0], 'enrollment_date' => '2026-09-03', 'grade' => NULL],
    ];

    foreach ($sampleEnrollments as $en) {
        try {
            $stmtEnroll->execute([
                ':user_id'         => $en['user_id'],
                ':course_id'       => $en['course_id'],
                ':enrollment_date' => $en['enrollment_date'],
                ':grade'           => $en['grade'],
            ]);
            $enrollCount++;
        } catch (PDOException $e) {
            // ข้ามถ้ามี error
        }
    }
}
echo "✅ บันทึกการลงทะเบียน (Enrollments) สำเร็จ: {$enrollCount} รายการ<br>";

echo "<br>🎉 <strong>Seed ข้อมูลทั้งหมดลง 3 ตารางเรียบร้อยแล้ว!</strong><br>";
echo "<br><a href='/course-registration/pages/login.php'>ไปหน้า Login</a>";

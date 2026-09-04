<?php
// =============================================
// Database Connection (การเชื่อมต่อ Database)
// =============================================
// ไฟล์นี้ทำหน้าที่เดียวคือ: สร้างการเชื่อมต่อไปยัง MySQL
// ไฟล์อื่นๆ จะ require_once ไฟล์นี้เพื่อใช้ตัวแปร $pdo

// ตั้งค่าการเชื่อมต่อ
// ใน JavaScript จะเขียนแบบนี้: const host = 'localhost';
// ใน PHP ตัวแปรต้องขึ้นต้นด้วย $ เสมอ
$host = 'localhost';       // Server ที่ MySQL ทำงานอยู่ (XAMPP = localhost)
$dbname = 'course_registration';  // ชื่อ Database ที่เราสร้าง
$username = 'root';        // Username เริ่มต้นของ XAMPP
$password = '';            // Password เริ่มต้นของ XAMPP (ว่างเปล่า)

// สร้าง DSN (Data Source Name)
// DSN คือ "ที่อยู่" ของ Database เขียนเป็น string รูปแบบเฉพาะ
// เหมือน URL ที่บอกว่า Database อยู่ที่ไหน ใช้ตัวไหน
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// =============================================
// ตั้งค่า Options สำหรับ PDO
// =============================================
// options เป็น array — คล้ายกับ Object ใน JavaScript
// JavaScript: const options = { key: value }
// PHP:        $options = [key => value]
$options = [
    // ถ้าเกิด error ให้ throw Exception (เหมือน throw new Error ใน JS)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

    // ให้ผลลัพธ์จาก query เป็น associative array (คล้าย Object ใน JS)
    // เช่น $row['name'] แทนที่จะเป็น $row[0]
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// =============================================
// เชื่อมต่อ Database ด้วย try-catch
// =============================================
// try-catch ใน PHP ทำงานเหมือน JavaScript เลย
// ถ้าเชื่อมต่อสำเร็จ -> ได้ตัวแปร $pdo ไปใช้งาน
// ถ้าเชื่อมต่อไม่ได้ -> catch จะจับ error
try {
    // new PDO() = สร้าง Object สำหรับเชื่อมต่อ Database
    // เหมือน new mysql.createConnection() ใน Node.js
    $pdo = new PDO($dsn, $username, $password, $options);

} catch (PDOException $e) {
    // PDOException คือ class สำหรับ error ของ PDO
    // $e->getMessage() คือข้อความ error (เหมือน error.message ใน JS)
    die("การเชื่อมต่อ Database ล้มเหลว: " . $e->getMessage());
}

// =============================================
// วิธีใช้ไฟล์นี้ในไฟล์อื่น:
// =============================================
// require_once __DIR__ . '/../config/database.php';
// ตอนนี้ตัวแปร $pdo พร้อมใช้งานแล้ว!
// $stmt = $pdo->query("SELECT * FROM students");
// $students = $stmt->fetchAll();

<?php
// =============================================
// Database Connection Template (ไฟล์ตัวอย่าง)
// =============================================
// คัดลอกไฟล์นี้เป็น database.php แล้วตั้งค่าตามเครื่องของคุณ
// เช่น: cp config/database.example.php config/database.php

$host = 'localhost';              // Database host (XAMPP = localhost)
$dbname = 'course_registration';  // ชื่อ Database
$username = 'root';               // MySQL Username (XAMPP default: root)
$password = '';                   // MySQL Password (XAMPP default: ว่างเปล่า)

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("การเชื่อมต่อ Database ล้มเหลว: " . $e->getMessage());
}

<?php
// =============================================
// Header: ส่วนเปิดต้นของ HTML ทุกหน้า
// =============================================
// session_start() ต้องเรียกก่อน output ใดๆ
// เพื่อให้ $_SESSION ใช้งานได้ในทุกหน้า
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ไฟล์นี้จะถูก require_once ในทุกหน้า
// ทำหน้าที่: สร้าง <html>, <head>, เปิด <body>
//
// คิดเหมือน template ใน JavaScript:
// const header = `<html><head>...</head><body>`;
// แต่ PHP ทำได้ดีกว่าเพราะสามารถ "ผสม" HTML กับ PHP ได้เลย
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบลงทะเบียนเรียน | Course Registration System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/course-registration/css/style.css?v=<?= time() ?>">
</head>
<body>

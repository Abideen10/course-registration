# 🎓 Course Registration System

ระบบลงทะเบียนเรียนขนาดเล็กสำหรับมหาวิทยาลัย  
พัฒนาเพื่อเรียนรู้ PHP, PDO, CRUD Operations

## 📋 Features

- **Student Management** — เพิ่ม แสดง แก้ไข ลบ นักศึกษา
- **Course Management** — เพิ่ม แสดง แก้ไข ลบ รายวิชา
- **Enrollment Management** — ลงทะเบียน แสดง แก้ไขเกรด ยกเลิกการลงทะเบียน
- **Dashboard** — แสดงสถิติและการลงทะเบียนล่าสุด

## 🛠 Tech Stack

| ส่วน | เทคโนโลยี |
|------|-----------|
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Backend | PHP, PDO |
| Database | MySQL / MariaDB (XAMPP) |
| Tools | VS Code, XAMPP, Git |

## 📁 Project Structure

```
course-registration/
├── index.php                 # Dashboard (หน้าแรก)
├── config/
│   └── database.php          # การเชื่อมต่อ Database (PDO)
├── pages/
│   ├── students.php          # หน้าจัดการนักศึกษา
│   ├── courses.php           # หน้าจัดการรายวิชา
│   └── enrollments.php       # หน้าจัดการลงทะเบียน
├── actions/
│   ├── student_create.php    # เพิ่มนักศึกษา (INSERT)
│   ├── student_update.php    # แก้ไขนักศึกษา (UPDATE)
│   ├── student_delete.php    # ลบนักศึกษา (DELETE)
│   ├── course_create.php     # เพิ่มรายวิชา
│   ├── course_update.php     # แก้ไขรายวิชา
│   ├── course_delete.php     # ลบรายวิชา
│   ├── enrollment_create.php # ลงทะเบียนเรียน
│   ├── enrollment_update.php # แก้ไขเกรด
│   └── enrollment_delete.php # ยกเลิกการลงทะเบียน
├── includes/
│   ├── header.php            # HTML head + เปิด body
│   ├── navbar.php            # แถบเมนูนำทาง
│   └── footer.php            # ปิด body + โหลด JS
├── css/
│   └── style.css             # Dark theme UI
├── js/
│   └── app.js                # JavaScript utilities
├── database/
│   └── database.sql          # SQL สร้าง Database
└── README.md
```

## 🗄 Database Design

```
students              enrollments              courses
┌──────────────┐      ┌─────────────────┐      ┌──────────────┐
│ id (PK)      │──┐   │ id (PK)         │   ┌──│ id (PK)      │
│ student_code │  │   │ student_id (FK) │───┘  │ course_code  │
│ name         │  └──>│ course_id (FK)  │      │ course_name  │
│ email        │      │ enrollment_date │      │ credits      │
│ department   │      │ grade           │      │ teacher      │
└──────────────┘      └─────────────────┘      └──────────────┘
       1 : N                                          1 : N
```

## 🚀 Setup

1. **ติดตั้ง XAMPP** แล้วเปิด Apache + MySQL

2. **Clone โปรเจค** ไปที่ `htdocs/`:
   ```
   cd C:\xampp\htdocs
   git clone <repository-url> course-registration
   ```

3. **สร้าง Database**:
   - เปิด phpMyAdmin: `http://localhost/phpmyadmin`
   - ไปที่แท็บ SQL
   - Copy เนื้อหาจาก `database/database.sql` ไปวาง แล้วกด Go

4. **เปิดเว็บ**:
   ```
   http://localhost/course-registration/
   ```

## 🔒 Security

- ใช้ **PDO Prepared Statements** ป้องกัน SQL Injection
- ใช้ **htmlspecialchars()** ป้องกัน XSS
- **Validate ข้อมูลฝั่ง Server** ทุกครั้ง
- ใช้ **POST method** สำหรับ Create/Update/Delete

## 📖 PHP Concepts ที่ใช้

| Concept | คำอธิบาย |
|---------|----------|
| `$_GET` | รับข้อมูลจาก URL query string |
| `$_POST` | รับข้อมูลจาก HTML Form |
| `require_once` | นำเข้าไฟล์ PHP อื่น |
| `PDO` | เชื่อมต่อ Database |
| `prepare() / execute()` | Prepared Statements ป้องกัน SQL Injection |
| `fetch() / fetchAll()` | ดึงข้อมูลจาก query result |
| `header('Location: ...')` | Redirect ไปหน้าอื่น |
| `htmlspecialchars()` | ป้องกัน XSS |
| `isset() / empty()` | ตรวจสอบค่าตัวแปร |
| `foreach` | วนลูป array |
| `try-catch` | จัดการ error |

<?php
$host = "localhost";       // ชื่อโฮสต์
$username = "root";        // ชื่อผู้ใช้ MySQL
$password = "";            // รหัสผ่าน MySQL
$database = "medical_equipment_lending";  // ชื่อฐานข้อมูล
$base_url = "localhost/BRppao";

$conn = new mysqli($host, $username, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

?>

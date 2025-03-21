<?php
// ไม่ต้อง session_start(); เพราะไม่ต้องการ Login

// เรียกไฟล์เชื่อมต่อฐานข้อมูล
include("includes/db_connect.php");

// ดึงข้อมูลครุภัณฑ์ พร้อมเชื่อมตาราง asset_categories (เพื่อแสดงประเภท)
$sql = "SELECT a.*, c.name AS category_name 
        FROM assets a
        LEFT JOIN asset_categories c ON a.category_id = c.category_id
        ORDER BY a.asset_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการครุภัณฑ์ (Public View)</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/custom.css">
</head>
<body>

<!-- ส่วนหัวเว็บแบบง่าย ๆ (ถ้าไม่อยาก include header.php) -->
<div class="container mt-4">
    <h3>รายการครุภัณฑ์</h3>
    <p class="text-muted">หน้านี้สามารถเข้าชมได้โดยไม่ต้องล็อกอิน</p>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อครุภัณฑ์</th>
                <th>ประเภท</th>
                <th>จำนวน</th>
                <th>สถานะ</th>
                <th>สถานที่จัดเก็บ</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['asset_id']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['category_name']; ?></td>
                    <td><?= $row['quantity']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td><?= $row['location']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- ไฟล์ JS (หากต้องการ) -->
<script src="assets/script.js"></script>
</body>
</html>

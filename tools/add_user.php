<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

// ตรวจสอบว่าล็อกอินหรือยัง และเป็น admin หรือไม่
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include("includes/db_connect.php");
include("includes/header.php");
include("includes/sidebar.php");

// ตัวแปรสำหรับเก็บข้อความแสดงผล (success, error)
$success = "";
$error = "";

// เมื่อฟอร์มถูก submit (method=POST)
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // ตรวจสอบว่าผู้ใช้เคยมีในระบบหรือไม่ (username unique)
    $checkSql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $resultCheck = $stmt->get_result();

    if($resultCheck->num_rows > 0) {
        // พบ username ซ้ำ
        $error = "ชื่อผู้ใช้นี้ถูกใช้งานแล้ว กรุณาเปลี่ยนชื่อผู้ใช้ใหม่!";
    } else {
        // ถ้าไม่ซ้ำ -> จัดการ Hash Password และบันทึกข้อมูล
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertSql = "INSERT INTO users (username, password, fullname, role) 
                      VALUES (?, ?, ?, ?)";
        $stmt2 = $conn->prepare($insertSql);
        $stmt2->bind_param("ssss", $username, $hashedPassword, $fullname, $role);

        if($stmt2->execute()) {
            $success = "เพิ่มผู้ใช้ใหม่สำเร็จ!";
        } else {
            $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มผู้ใช้ - Admin Panel</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/custom.css">
</head>
<body>

<!-- เรียกใช้ Sidebar -->
<?php include("includes/sidebar.php"); ?>

<div class="container-fluid">
    <h3 class="mt-4 mb-3">เพิ่มผู้ใช้</h3>

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <div class="card p-3" style="max-width: 600px;">
        <form method="POST">
            <div class="mb-3">
                <label for="fullname" class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" id="fullname" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-control" required>
                <!-- ถ้าอยากให้มีปุ่มสลับแสดงรหัสผ่าน สามารถใช้ JS script.js -->
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">สิทธิ์ (Role)</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="users.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>

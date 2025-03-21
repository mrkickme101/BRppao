<?php
// ไม่ต้อง session_start() ในหน้านี้ ถ้าเปิดให้ทุกคนเข้าถึง
// แต่ถ้าต้องการตรวจสอบว่าถ้า login แล้วไม่ควรเข้า register ก็เพิ่ม logic ได้

include("includes/db_connect.php");

// ตัวแปรเก็บข้อความสถานะ
$success = "";
$error = "";

// ตรวจสอบการ submit แบบ POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // ตรวจสอบความถูกต้องเบื้องต้น
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน.";
    } else if ($password !== $confirm_password) {
        $error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน.";
    } else {
        // ตรวจสอบว่า username ถูกใช้แล้วหรือไม่
        $check_sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows > 0) {
            // พบ username ซ้ำ
            $error = "ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว กรุณาใช้ชื่ออื่น.";
        } else {
            // ถ้าไม่ซ้ำ -> ทำการเข้ารหัสรหัสผ่าน
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // role กำหนดเป็น employee (หรือค่าอื่นตามต้องการ)
            $role = "employee";

            // เตรียม Insert
            $insert_sql = "INSERT INTO users (username, password, fullname, email, phone, role) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param("ssssss", $username, $hashed_password, $fullname, $email, $phone, $role);

            if ($stmt_insert->execute()) {
                $success = "สมัครสมาชิกสำเร็จ! คุณสามารถเข้าสู่ระบบได้แล้ว.";
            } else {
                $error = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/custom.css">
</head>
<body>

<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4" style="max-width: 500px; width: 100%;">
        <h3 class="mb-3">สมัครสมาชิก</h3>
        
        <!-- แสดงผลข้อความ Error/Success -->
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" placeholder="ระบุชื่อ-นามสกุล" value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" placeholder="เช่น user123" required value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>รหัสผ่าน <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="••••••" required>
            </div>
            <div class="mb-3">
                <label>ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••" required>
            </div>
            <div class="mb-3">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label>เบอร์โทร</label>
                <input type="text" name="phone" class="form-control" placeholder="เช่น 0812345678" value="<?= isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100">สมัครสมาชิก</button>
        </form>
        
        <!-- ลิงก์ย้อนกลับไปหน้า Login (ถ้ามี) -->
        <div class="mt-3 text-center">
            <a href="login.php">กลับสู่หน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>

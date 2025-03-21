<?php
// (ไม่บังคับ) หากต้องการให้เฉพาะ Admin ที่ล็อกอินแล้วสามารถเข้าหน้าได้ ให้ใช้ session + ตรวจสอบ role
// ตัวอย่างเช่น:
// session_start();
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: login.php");
//     exit;
// }

include("includes/db_connect.php");

// ตัวแปรสำหรับแสดงข้อความผลลัพธ์
$success = "";
$error = "";

// ตรวจสอบว่าเป็นการ Submit แบบ POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // ตรวจสอบข้อมูลเบื้องต้น
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน";
    } else if ($password !== $confirm_password) {
        $error = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจซ้ำว่ามี username ในระบบแล้วหรือไม่
        $check_sql = "SELECT user_id FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = "ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว กรุณาใช้ชื่ออื่น";
        } else {
            // เข้ารหัสรหัสผ่าน
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // ระบุ role = 'admin'
            $role = 'admin';

            // Insert ลงตาราง users
            $sql_insert = "INSERT INTO users (username, password, fullname, email, phone, role) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssss", $username, $hashed_password, $fullname, $email, $phone, $role);

            if ($stmt_insert->execute()) {
                $success = "สร้างบัญชี Admin ใหม่เรียบร้อย";
            } else {
                $error = "เกิดข้อผิดพลาด: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลงทะเบียน Admin</title>
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/custom.css">
</head>
<body>

<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4" style="max-width: 500px; width: 100%;">
        <h3 class="mb-3">ลงทะเบียน Admin</h3>

        <!-- แสดงข้อความผลลัพธ์ -->
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" 
                       value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : ''; ?>">
            </div>

            <div class="mb-3">
                <label>ชื่อผู้ใช้ (Username) <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control" required
                       value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
            </div>

            <div class="mb-3">
                <label>รหัสผ่าน <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control"
                       value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
            </div>

            <div class="mb-3">
                <label>เบอร์โทร</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
            </div>

            <button type="submit" class="btn btn-primary w-100">สร้างบัญชี Admin</button>
        </form>
        
        <div class="mt-3 text-center">
            <a href="login.php">กลับสู่หน้าเข้าสู่ระบบ</a>
        </div>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>

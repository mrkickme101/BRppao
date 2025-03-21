<?php
session_start();
include("includes/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบผู้ใช้จาก DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // ตรวจสอบ password
        // ถ้าเก็บ password แบบ hash ให้ใช้ password_verify
        // แต่ถ้าเก็บ Plain text (ไม่แนะนำ) ก็เทียบตรงๆ
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        }
    }
    // ถ้าผิด แสดงข้อความ
    $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="height:100vh;">
    <div class="card p-4 shadow-sm" style="width: 350px;">
        <h4 class="text-center mb-3">เข้าสู่ระบบ</h4>
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label>ชื่อผู้ใช้</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label>รหัสผ่าน</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
        </form>
    </div>
</div>
</body>
</html>

<?php
session_start();
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
//   }
  
  include("includes/db_connect.php");

// ถ้าต้องการเพิ่ม/ลบ/แก้ไข ให้ตรวจสอบค่าจาก $_POST/$_GET
// ตัวอย่าง แสดงข้อมูลผู้ใช้ทั้งหมด
$sql = "SELECT u.*, d.name AS department_name 
        FROM users u
        LEFT JOIN departments d ON u.department_id = d.department_id
        ORDER BY u.user_id DESC";
$result = $conn->query($sql);

include("includes/header.php");
include("includes/sidebar.php");
?>
<div class="container-fluid" style="margin-left:260px; padding: 20px;">
    <h3>👥 จัดการบุคลากร</h3>
    <a href="?action=add" class="btn btn-success mb-3">+ เพิ่มบุคลากร</a>

    <?php
    // ตัวอย่างแบบฟอร์มเพิ่ม
    if(isset($_GET['action']) && $_GET['action'] == 'add') {
        // แสดงฟอร์มเพิ่มบุคลากร
        ?>
        <form method="POST" action="users.php">
            <input type="hidden" name="action" value="insert">
            <div class="mb-2">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>ชื่อผู้ใช้</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-2">
                <label>อีเมล</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-2">
                <label>เบอร์โทร</label>
                <input type="text" name="phone" class="form-control">
            </div>
            <div class="mb-2">
                <label>หน่วยงาน</label>
                <select name="department_id" class="form-control">
                    <option value="">--เลือกหน่วยงาน--</option>
                    <?php
                    // ดึงหน่วยงาน
                    $dep = $conn->query("SELECT * FROM departments");
                    while($d = $dep->fetch_assoc()) {
                        echo "<option value='{$d['department_id']}'>{$d['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-2">
                <label>บทบาท</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="employee">Employee</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
        </form>
        <?php
    }

    // จัดการเมื่อ submit form เพิ่มข้อมูล
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'insert') {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dept_id = $_POST['department_id'] ? $_POST['department_id'] : null;
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, email, phone, role, department_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $username, $password, $fullname, $email, $phone, $role, $dept_id);
        $stmt->execute();
        echo "<div class='alert alert-success'>เพิ่มบุคลากรสำเร็จ</div>";
        echo "<script>setTimeout(()=>{ window.location='users.php'; },1500);</script>";
    }
    ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ชื่อ</th>
                <th>ชื่อผู้ใช้</th>
                <th>อีเมล</th>
                <th>หน่วยงาน</th>
                <th>บทบาท</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['fullname']; ?></td>
                <td><?= $row['username']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['department_name']; ?></td>
                <td><?= $row['role']; ?></td>
                <td>
                    <!-- ตัวอย่างปุ่มแก้ไข/ลบ -->
                    <a href="?action=edit&id=<?= $row['user_id']; ?>" class="btn btn-sm btn-primary">แก้ไข</a>
                    <a href="?action=delete&id=<?= $row['user_id']; ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('ยืนยันการลบ?');">ลบ</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php include("includes/footer.php"); ?>

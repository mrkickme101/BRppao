<?php
session_start();
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
//   }
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include("includes/db_connect.php");

// ดึงข้อมูลหน่วยงาน
$sql = "SELECT * FROM departments ORDER BY department_id DESC";
$result = $conn->query($sql);

// เพิ่ม/แก้ไข/ลบ
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['action']) && $_POST['action'] == 'add') {
        $name = $_POST['name'];
        $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }
}

include("includes/header.php");
include("includes/sidebar.php");
?>
<div class="container-fluid" style="margin-left:260px; padding: 20px;">
    <h3>🏢 จัดการหน่วยงาน</h3>
    <form method="POST" class="row g-3 mb-4">
        <input type="hidden" name="action" value="add">
        <div class="col-auto">
            <label for="name" class="visually-hidden">ชื่อหน่วยงาน</label>
            <input type="text" class="form-control" name="name" placeholder="ชื่อหน่วยงาน" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">+ เพิ่มหน่วยงาน</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ชื่อหน่วยงาน</th>
            <th>จัดการ</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['department_id']; ?></td>
                <td><?= $row['name']; ?></td>
                <td>
                    <!-- ปุ่มแก้ไข/ลบ -->
                    <button class="btn btn-sm btn-primary">แก้ไข</button>
                    <button class="btn btn-sm btn-danger">ลบ</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("includes/footer.php"); ?>

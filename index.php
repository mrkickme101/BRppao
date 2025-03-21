<?php

session_start();
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

  if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include("includes/db_connect.php");

// ดึงข้อมูลสรุป
$sql_assets = "SELECT COUNT(*) AS total_assets FROM assets";
$sql_requests = "SELECT COUNT(*) AS total_requests FROM supply_requests";
$total_assets = $conn->query($sql_assets)->fetch_assoc()['total_assets'];
$total_requests = $conn->query($sql_requests)->fetch_assoc()['total_requests'];
?>

<?php include("includes/header.php"); ?>
<!-- เรียกใช้ sidebar -->
<?php include("includes/sidebar.php"); ?>

<div class="container-fluid" style="margin-left:260px; padding: 20px;">
    <h3>📊 Dashboard</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3 mb-3">
                <h5>ยอดครุภัณฑ์</h5>
                <p><?= $total_assets; ?> รายการ</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 mb-3">
                <h5>การเบิก-จ่ายวัสดุ</h5>
                <p><?= $total_requests; ?> รายการ</p>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
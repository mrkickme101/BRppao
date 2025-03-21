<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

// ดึงข้อมูลวัสดุจากตาราง supplies
$sql = "SELECT * FROM supplies ORDER BY supply_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>📦 รายงานวัสดุ</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ชื่อวัสดุ</th>
            <th>จำนวน</th>
            <th>หน่วย</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['supply_id']; ?></td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['quantity']; ?></td>
                <td><?= $row['unit']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$sql = "SELECT da.*, a.name AS asset_name, u.fullname AS report_by
        FROM damaged_assets da
        JOIN assets a ON da.asset_id = a.asset_id
        JOIN users u ON da.user_id = u.user_id
        ORDER BY da.damage_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>⚠️ รายการครุภัณฑ์ชำรุด</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ครุภัณฑ์</th>
            <th>ผู้แจ้ง</th>
            <th>รายละเอียด</th>
            <th>วันที่แจ้ง</th>
            <th>สถานะ</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['damage_id']; ?></td>
                <td><?= $row['asset_name']; ?></td>
                <td><?= $row['report_by']; ?></td>
                <td><?= $row['description']; ?></td>
                <td><?= $row['report_date']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

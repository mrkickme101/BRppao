<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$sql = "SELECT rr.*, a.name AS asset_name, u.fullname AS report_by
        FROM repair_requests rr
        JOIN assets a ON rr.asset_id = a.asset_id
        JOIN users u ON rr.user_id = u.user_id
        ORDER BY rr.repair_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>🔧 รายการแจ้งซ่อมครุภัณฑ์</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ครุภัณฑ์</th>
            <th>ผู้แจ้ง</th>
            <th>รายละเอียด</th>
            <th>วันที่แจ้ง</th>
            <th>สถานะ</th>
            <th>วันที่ซ่อมเสร็จ</th>
            <th>ค่าใช้จ่าย</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['repair_id']; ?></td>
                <td><?= $row['asset_name']; ?></td>
                <td><?= $row['report_by']; ?></td>
                <td><?= $row['description']; ?></td>
                <td><?= $row['request_date']; ?></td>
                <td><?= $row['status']; ?></td>
                <td><?= $row['completion_date']; ?></td>
                <td><?= $row['repair_cost']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

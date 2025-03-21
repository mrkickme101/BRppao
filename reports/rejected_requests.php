<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$sql = "SELECT sr.*, s.name AS supply_name, u.fullname AS request_by
        FROM supply_requests sr
        JOIN supplies s ON sr.supply_id = s.supply_id
        JOIN users u ON sr.user_id = u.user_id
        WHERE sr.status = 'rejected'
        ORDER BY sr.request_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>❌ รายงานไม่อนุมัติ</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัสคำขอ</th>
            <th>ชื่อวัสดุ</th>
            <th>ผู้ขอ</th>
            <th>สถานะ</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['request_id']; ?></td>
                <td><?= $row['supply_name']; ?></td>
                <td><?= $row['request_by']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

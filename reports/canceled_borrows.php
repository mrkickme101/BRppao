<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$sql = "SELECT br.*, a.name AS asset_name, u.fullname AS user_name
        FROM borrow_records br
        JOIN assets a ON br.asset_id = a.asset_id
        JOIN users u ON br.user_id = u.user_id
        WHERE br.status = 'canceled'
        ORDER BY br.borrow_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>🚫 รายการยืมครุภัณฑ์ที่ถูกยกเลิก</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ผู้ยืม</th>
            <th>ครุภัณฑ์</th>
            <th>วันที่ยืม</th>
            <th>กำหนดคืน</th>
            <th>สถานะ</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['borrow_id']; ?></td>
                <td><?= $row['user_name']; ?></td>
                <td><?= $row['asset_name']; ?></td>
                <td><?= $row['borrow_date']; ?></td>
                <td><?= $row['return_date']; ?></td>
                <td><?= $row['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

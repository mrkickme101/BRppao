<?php
include("../includes/db_connect.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$sql = "SELECT a.*, c.name AS category_name
        FROM assets a
        LEFT JOIN asset_categories c ON a.category_id = c.category_id
        ORDER BY a.asset_id DESC";
$result = $conn->query($sql);
?>
<div class="container-fluid" style="margin-left:260px; padding:20px;">
    <h3>📋 รายงานยอดครุภัณฑ์</h3>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>รหัส</th>
            <th>ชื่อครุภัณฑ์</th>
            <th>ประเภท</th>
            <th>สถานะ</th>
            <th>จำนวน</th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['asset_id']; ?></td>
                <td><?= $row['name']; ?></td>
                <td><?= $row['category_name']; ?></td>
                <td><?= $row['status']; ?></td>
                <td><?= $row['quantity']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php include("../includes/footer.php"); ?>

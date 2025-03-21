<?php
// ตรวจสอบ Session หรือ Role ก่อน แนะนำให้ทำในไฟล์ header หรือทุกไฟล์ที่ต้องการสิทธิ์ Admin
// session_start();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<?php include("db_connect.php"); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="position: fixed; width: 250px; height: 100vh;">
  <div class="d-flex flex-column align-items-start p-3 text-white" style="width: 100%;">
    <h4 class="mb-4">Admin Panel</h4>
    <ul class="nav nav-pills flex-column mb-auto w-100">
      <li class="nav-item">
        <a href="index.php" class="nav-link text-white">📊 Dashboard</a>
      </li>
      <li>
        <a href="users.php" class="nav-link text-white">👥 จัดการบุคลากร</a>
      </li>
      <li>
        <a href="departments.php" class="nav-link text-white">🏢 จัดการหน่วยงาน</a>
      </li>
      <li>
        <a href="reports/stock.php" class="nav-link text-white">📦 รายงานวัสดุ</a>
      </li>
      <li>
        <a href="reports/assets.php" class="nav-link text-white">📋 รายงานครุภัณฑ์</a>
      </li>
      <li>
        <a href="reports/requests.php" class="nav-link text-white">📝 รายงานเบิก-จ่าย</a>
      </li>
      <li>
        <a href="reports/rejected_requests.php" class="nav-link text-white">❌ ไม่อนุมัติ</a>
      </li>
      <li>
        <a href="reports/borrow_records.php" class="nav-link text-white">🔄 การยืม-คืน</a>
      </li>
      <li>
        <a href="reports/canceled_borrows.php" class="nav-link text-white">🚫 ยกเลิกยืม</a>
      </li>
      <li>
        <a href="reports/damaged_assets.php" class="nav-link text-white">⚠️ ครุภัณฑ์ชำรุด</a>
      </li>
      <li>
        <a href="reports/lost_assets.php" class="nav-link text-white">❓ ครุภัณฑ์สูญหาย</a>
      </li>
      <li>
        <a href="reports/repair_requests.php" class="nav-link text-white">🔧 แจ้งซ่อม</a>
      </li>
      <li>
        <a href="logout.php" class="nav-link text-danger mt-3">🚪 ออกจากระบบ</a>
      </li>
    </ul>
  </div>
</nav>


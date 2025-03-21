<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  include("includes/header.php");

session_destroy();
header("Location: login.php");
exit;
?>

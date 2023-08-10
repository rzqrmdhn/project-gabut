<?php
session_start();
session_destroy();
header("Location: index.php"); // Ganti index.php dengan halaman login Anda
exit();
?>

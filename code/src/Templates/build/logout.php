<?php
require_once 'main.php';
session_unset();
session_destroy();

header("location:login.php");
exit();
?>
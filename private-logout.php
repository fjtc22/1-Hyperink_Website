<?php
include 'private/functions.php';
session_start();
session_unset();
session_destroy();
header("Location:private-login.php");
?>

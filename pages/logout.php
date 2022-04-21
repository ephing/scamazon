<?php
require '../src/session.php';

unset($_SESSION["username"]);
unset($_SESSION["email"]);
unset($_SESSION["coupons"]);
unset($_SESSION["usedcoupons"]);
header("Location: ../index.php");
?>
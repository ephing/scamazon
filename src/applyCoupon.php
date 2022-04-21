<?php
require 'session.php';
$config = require 'config.php';

$couponcode = $_POST["couponcode"];

$sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

if (isset($_SESSION["username"])) {
    if (!in_array($couponcode, $_SESSION["usedcoupons"])) {
        $query = $sql->prepare("SELECT * FROM Coupon WHERE coupon=?;");
        $params = [$couponcode];
        $query->bind_param('s', ...$params);
        $query->execute();

        if ($row = $query->get_result()->fetch_assoc()) {
            $_SESSION["usedcoupons"][] = $couponcode;
            $_SESSION["coupons"][$row["category"]] = $row["discount"];
        }
    }
}

$sql->close();
?>
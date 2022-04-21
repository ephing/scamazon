<?php
require '../src/session.php';
$config = require '../src/config.php';

$sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

$ccn = '' . $_POST["ccn"];
$expdate = $_POST["expdate"];
$nums = $_POST["3num"];

if (isset($_SESSION["username"])) {
    // save credit card info (very secure)
    $query = $sql->prepare("UPDATE `User` SET `ccnum`=?,`ccdate`=?,`cccode`=? WHERE email=?;");
    $params = [$ccn, $expdate, $nums, $_SESSION["email"]];
    $query->bind_param('ssis', ...$params);
    $query->execute();

    $query = $sql->prepare("SELECT * FROM Cart INNER JOIN Item ON Cart.itemid=Item.itemid WHERE email=?;");
    $params = [$_SESSION["email"]];
    $query->bind_param('s', ...$params);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        $check = $sql->prepare("SELECT * FROM Orders WHERE email=? AND itemid=?;");
        $params = [$_SESSION["email"], $row["itemid"]];
        $check->bind_param("si", ...$params);
        $check->execute();

        if ($row2 = $check->get_result()->fetch_assoc()) {
            $order = $sql->prepare("UPDATE Orders SET `cost`=?, `quantity`=? WHERE itemid=? AND email=?;");
            if ($_SESSION["coupons"][$row["category"]] <> 0) {
                $params = [$row2["cost"] + ($row["quantity"] * $row["price"] * $_SESSION["coupons"][$row["category"]]),
                            $row["quantity"] + $row2["quantity"],
                            $row["itemid"],
                            $_SESSION["email"]];
            } else {
                $params = [$row2["cost"] + ($row["quantity"] * $row["price"]),
                            $row["quantity"] + $row2["quantity"],
                            $row["itemid"],
                            $_SESSION["email"]];
            }
            $order->bind_param('diis', ...$params);
            $order->execute();
        } else {
            $order = $sql->prepare("INSERT INTO `Orders`(`email`, `itemid`, `quantity`, `cost`) VALUES (?,?,?,?)");
            if ($_SESSION["coupons"][$row["category"]] <> 0) {
                $params = [$_SESSION["email"], 
                            $row["itemid"], 
                            $row["quantity"], 
                            $row["quantity"] * $row["price"] * $_SESSION["coupons"][$row["category"]]];
            } else {
                $params = [$_SESSION["email"], 
                            $row["itemid"], 
                            $row["quantity"], 
                            $row["quantity"] * $row["price"]];
            }
            $order->bind_param("siid", ...$params);
            $order->execute();
        }

        $remcart = $sql->prepare("DELETE FROM Cart WHERE email=? AND itemid=?;");
        $params = [$_SESSION["email"], $row["itemid"]];
        $remcart->bind_param("si", ...$params);
        $remcart->execute();
    }

    unset($_SESSION["usedcoupons"]);
    unset($_SESSION["coupons"]);
}

$sql->close();

header("Location: ../index.php");
?>
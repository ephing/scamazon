<?php
require 'session.php';
$config = require 'config.php';

$sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

$item = $_POST["itemid"];
$quantity = $_POST["quantity"];

if (isset($_SESSION["username"])) {
    $query = $sql->prepare("SELECT * FROM Cart WHERE itemid=? AND email=?;");
    $params = [$item, $_SESSION["email"]];
    $query->bind_param('ss', ...$params);
    $query->execute();

    if ($row = $query->get_result()->fetch_assoc()) {
        $query = $sql->prepare("UPDATE Cart SET `quantity`=? WHERE itemid=? AND email=?;");
        $params = [$row["quantity"] + $quantity, $item, $_SESSION["email"]];
        $query->bind_param('sss', ...$params);
        $query->execute();
    } else {
        $query = $sql->prepare("INSERT INTO Cart (`itemid`, `email`, `quantity`) VALUES (?,?,?)");
        $params = [$item, $_SESSION["email"], $quantity];
        $query->bind_param('sss', ...$params);
        $query->execute();
    }
}

$sql->close();

?>
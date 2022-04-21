<?php
require '../src/session.php';
$config = require '../src/config.php';

$token = $_POST["token"];

// added csrf tokens to try to prevent Garrett from adding hundreds of users to my database
if (!$token || $token !== $_SESSION['token']) {
    // return 405 http status code
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

$username = $_POST["username"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);

$sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

if ($sql->connect_errno) {
    printf("Connect failed: %s\n", $sql->connect_error);
    exit();
}

$query = $sql->prepare("INSERT INTO User (`name`, `email`, `password`) VALUES (?,?,?)");
$params = [$username, $email, $password];
$query->bind_param('sss', ...$params);

if ($query->execute()) {
    $sql->close();
    $_SESSION["username"] = $username;
    $_SESSION["email"] = $email;
    header("Location: ../index.php");
} else {
    $sql->close();
    echo "Error";
}
?>
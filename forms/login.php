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

$email = $_POST["email"];
$password = $_POST["password"];

$sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

if ($sql->connect_errno) {
    printf("Connect failed: %s\n", $sql->connect_error);
    exit();
}

$query = $sql->prepare("SELECT name, password FROM User WHERE email=?;");
$query->bind_param('s', $email);
$query->execute();

if ($result = $query->get_result()) {
    while ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row["password"])) {
            $sql->close();
            $_SESSION["username"] = $row["name"];
            $_SESSION["email"] = $email;
            header("Location: ../index.php");
        } else {
            echo "Invalid username or password.";
            $sql->close();
            header("Location: login.php");
        }
    }
} else {
    $sql->close();
    echo "Error: query went wrong<br />";
}

$sql->close();

?>
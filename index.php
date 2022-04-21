<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles/css.css" />
        <meta charset="utf-8" />
        <title>Scamazon</title>
        <link rel="shortcut icon" href="./res/favicon.ico" />
        <script src="src/main.js"></script>
    </head>
    <body>
        <div id="navbar">
            <a class="navlink" href="">Main Page</a>
            <img src="res/scamazon.png" alt="SCAMAZON"/>
            <?php
            require 'src/session.php';
            if (isset($_SESSION["username"])) {
                ?>
                <a class="userlink" href="pages/pastOrders.php">Orders</a>
                <a class="userlink" href="pages/cart.php">Cart</a>
                <a class="userlink" href="pages/logout.php">Sign Out</a>
                <a class="userlink">Welcome <?=$_SESSION["username"]?></a>
                <?php
            } else {
                ?>
                <a class="userlink" href="pages/createuser.php">Sign Up</a>
                <a class="userlink" href="pages/login.php">Log In</a>
                <?php
            }
            ?>
        </div>
        <div id="itemlist">
            <?php
            $config = require 'src/config.php';

            $sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

            if ($sql->connect_errno) {
                echo "Connect failed: %s\n" . $sql->connect_error;
                exit();
            }

            $query = "SELECT * from Item;";

            if ($result = $sql->query($query)) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                <div id="item"><h3><?=$row["name"];?></h3>
                    <img src="<?=$row["imgurl"];?>" /><br />
                    <button onclick="window.addToCart('<?=$row["itemid"]; ?>')">Add to Cart</button>
                    <input type="number" min=2 value=2 id="qty-<?=$row["itemid"];?>" />
                </div>
                    <?php
                }
            } else {
                echo "Error: there was an error.";
            }

            $sql->close();
            ?>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html>
    <?php
    require '../src/session.php';
    if (!isset($_SESSION["username"])) {
        header("Location: ../index.php");
    }
    ?>
    <head>
        <link rel="stylesheet" type="text/css" href="../styles/css.css" />
        <meta charset="utf-8" />
        <title>Scamazon - Orders</title>
        <link rel="shortcut icon" href="../res/favicon.ico" />
    </head>
    <body>
        <div id="navbar">
            <a class="navlink" href="../index.php">Main Page</a>
            <img src="../res/scamazon.png" alt="SCAMAZON"/><!--replace with img logo-->
            <a class="userlink" href="">Orders</a>
            <a class="userlink" href="cart.php">Cart</a>
            <a class="userlink" href="logout.php">Sign Out</a>
            <a class="userlink">Welcome <?=$_SESSION["username"]?></a>
        </div>
        <div id="orderitemlist">
            <?php
            $config = require("../src/config.php");

            $sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

            $query = $sql->prepare("SELECT * FROM Orders INNER JOIN Item ON Orders.itemid=Item.itemid WHERE email=?;");
            $params = [$_SESSION["email"]];
            $query->bind_param("s", ...$params);
            $query->execute();

            $result = $query->get_result();
            $total = 0;

            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="orderitem">
                    <img class="itemdesc" src="<?="../".$row["imgurl"]?>" />
                    <p class="itemdesc"><b>Name:</b><br /><?=$row["name"]?></p>
                    <p class="itemdesc"><b>Amount:</b><br /><?=$row["quantity"]?></p>
                    <p class="itemdesc"><b>Cost:</b><br />$<?=number_format($row["cost"], 2)?></p>
                </div>
                <?php
                $total += $row["cost"];
            }
            echo "<p>Total: " . $total . "</p>";
            $sql->close();
            ?>
        </div>
    </body>
</html>
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
        <title>Scamazon - Cart</title>
        <link rel="shortcut icon" href="../res/favicon.ico" />
        <script src="../src/main.js"></script>
    </head>
    <body>
        <div id="navbar">
            <a class="navlink" href="../index.php">Main Page</a>
            <img src="../res/scamazon.png" alt="SCAMAZON"/><!--replace with img logo-->
            <a class="userlink" href="pastOrders.php">Orders</a>
            <a class="userlink" href="">Cart</a>
            <a class="userlink" href="logout.php">Sign Out</a>
            <a class="userlink">Welcome <?=$_SESSION["username"]?></a>
        </div>
        <div id="cart"> 
            <?php
            $config = require '../src/config.php';

            $sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

            $query = $sql->prepare("SELECT * FROM Cart INNER JOIN Item ON Cart.itemid=Item.itemid WHERE email=?");
            $params = [$_SESSION["email"]];
            $query->bind_param('s', ...$params);
            $query->execute();
            $result = $query->get_result();
            $total = 0;

            echo "<div id=\"cartitemlist\">";
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="cartitem">
                    <img class="itemdesc" src="<?="../".$row["imgurl"]?>" />
                    <p class="itemdesc"><b>Name:</b><br /><?=$row["name"]?></p>
                    <p class="itemdesc"><b>Price:</b><br />$<?=number_format($row["price"], 2)?>&nbsp;&nbsp;</p>
                    <p class="itemdesc"><b>Amount:</b><br /><?=$row["quantity"]?></p></div>
                <?php
                if ($_SESSION["coupons"][$row["category"]] <> 0) {
                    $total += $row["quantity"] * $row["price"] * $_SESSION["coupons"][$row["category"]];
                } else {
                    $total += $row["quantity"] * $row["price"];
                }
            }
            $sql->close();
            ?>
            </div>
            <div id="totalbox">
                <p><b>Total:</b><br />$<?= number_format($total, 2); ?></p>
                <p>Coupons:</p>
                <ul>
                <?php
                foreach ($_SESSION["usedcoupons"] as $coupon) {
                    echo "<li>" . $coupon . "</li>";
                }
                ?>
                </ul>
                <input type="text" id="couponcode" pattern="[a-zA-Z0-9]*" /><br />
                <button onclick="window.applyCoupon()">Apply Coupon</button><br /><br />
                <button onclick="location.href = 'paymentinfo.php'">Place Order</button>
            </div>
        </div>
    </body>
</html>
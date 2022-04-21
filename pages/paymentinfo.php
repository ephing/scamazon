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
        <title>Scamazon - Place Order</title>
        <link rel="shortcut icon" href="../res/favicon.ico" />
    </head>
    <body>
        <div id="navbar">
            <a class="navlink" href="../index.php">Main Page</a>
            <img src="../res/scamazon.png" alt="SCAMAZON"/><!--replace with img logo-->
            <a class="userlink" href="cart.php">Cart</a>
            <a class="userlink" href="logout.php">Sign Out</a>
            <a class="userlink">Welcome <?=$_SESSION["username"]?></a>
        </div>
        <div>
            <form method="post" action="../src/placeOrder.php">
                <fieldset>
                    <legend><span>Billing Address</span></legend>
                    <label>Address 1:</label><input name="addr1" required />
                    <label>Address 2:</label><input name="addr2" />
                    <label>City:</label><input name="city" required />
                    <label>State</label>
                    <select name="state">
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District Of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                    </select>
                    <label>Zipcode</label><input name="zip" required />
                </fieldset>
                <fieldset>
                    <legend><span>Credit Card Info</span></legend>
                    <label>Credit Card Number</label>
                    <?php
                    $config = require("../src/config.php");
                    
                    $sql = new mysqli($config["url"], $config["username"], $config["password"], $config["dbname"]);

                    $query = $sql->prepare("SELECT ccnum, ccdate, cccode FROM User WHERE email=?");
                    $params = [$_SESSION["email"]];
                    $query->bind_param('s', ...$params);
                    $query->execute();
                    $result = $query->get_result();
                    $row = $result->fetch_assoc();
                    if ($row["ccnum"] == NULL) {
                    ?>
                    <input type="tel" name="ccn" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx" required />
                    <?php
                    } else {
                    ?>
                    <input type="tel" name="ccn" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx" required value="<?=$row["ccnum"]?>"/>
                    <?php
                    }
                    ?>
                    <label>Expiration Date</label>
                    <?php
                    if ($row["ccdate"] == NULL) {
                    ?>
                    <input type="month" name="expdate" required />
                    <?php
                    } else {
                    ?>
                    <input type="month" name="expdate" required value="<?=$row["ccdate"]?>" />
                    <?php
                    }
                    ?>
                    <label>The 3 Funny Numbers on the Back</label>
                    <?php
                    if ($row["cccode"] == NULL) {
                    ?>
                    <input type="tel" name="3num" inputmode="numeric" pattern="[0-9]*" maxlength="3" required />
                    <?php
                    } else {
                    ?>
                    <input type="tel" name="3num" inputmode="numeric" pattern="[0-9]*" maxlength="3" required value="<?=$row["cccode"]?>" />
                    <?php
                    }
                    ?>
                </fieldset>
                <fieldset>
                    <button type="submit">Place Order</button>
                </fieldset>
            </form>
        </div>
    </body>
</html>
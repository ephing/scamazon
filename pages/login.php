<!DOCTYPE html>
<html>
    <?php
    require '../src/session.php';
    if (isset($_SESSION["username"])) {
        header("Location: ../index.php");
    } else {
        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    }
    ?>
    <head>
        <link rel="stylesheet" type="text/css" href="../styles/css.css" />
        <meta charset="utf-8" />
        <title>Scamazon - Log In</title>
        <link rel="shortcut icon" href="../res/favicon.ico" />
    </head>
    <body>
        <div id="navbar">
            <a class="navlink" href="../index.php">Main Page</a>
            <img src="../res/scamazon.png" alt="SCAMAZON"/><!--replace with img logo-->
            <a class="userlink" href="createuser.php">Sign Up</a>
            <a class="userlink" href="">Log In</a>
        </div>
        <div id="content">
            <form action="../forms/login.php" method="post">
                <fieldset>
                    <legend><span>Log In:</span></legend>
                    <label>
                        Email: <input type="email" name="email" required />
                    </label>
                    <label>
                        Password: <input type="password" name="password" required />
                    </label>
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?: '' ?>">
                </fieldset>
                <fieldset id='buttonfield'>
                    <button type='submit'>Submit</button>
                </fieldset>
            </form>
        </div>
    </body>
</html>
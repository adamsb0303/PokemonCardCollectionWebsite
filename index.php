<!DOCTYPE html>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <title>Home</title>
    </head>
    <body>
        <?php
            include "php/header.php";
            if(count($_COOKIE) > 0) {
              echo "Cookies are enabled.\n";
            } else {
              echo "Cookies are disabled.\n";
            }
            if(!isset($_COOKIE["username"])) {
                echo "Cookie named 'username' is not set!";
              } else {
                echo "Cookie 'username' is set!<br>";
                echo "Value is: " . $_COOKIE['username'];
            }
        ?>
    </body>
</html>
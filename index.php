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
            if(!isset($_COOKIE["ID"])) {
                echo "Cookie named 'ID' is not set!";
              } else {
                echo "Cookie 'ID' is set!<br>";
                echo "ID is: " . $_COOKIE['ID'];
            }
        ?>
    </body>
</html>
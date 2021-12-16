<!DOCTYPE html>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <title>Home</title>
    </head>
    <body>
        <?php
            include "php/header.php";

            $sql = "SELECT user_value, user_cost FROM user";
        ?>
    </body>
</html>
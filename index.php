<!DOCTYPE html>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <title>Home</title>
    </head>
    <body>
        <?php
            include "php/header.php";

            if($signedIn){
                $sql = "SELECT user_value, user_cost FROM user
                        JOIN collection ON collection.user_id = user.user_id
                        WHERE `collection`.`user_id` = '" . $_COOKIE["ID"] . "'
                        AND `user_key` = '" . $_COOKIE["Key"] . "'";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $user_values = mysqli_fetch_array($result);

                echo "Inventory Value: $" . $user_values[0] . "<br/>";
                echo "Inventory Cost: $" . $user_values[1] . "<br/>";
                echo "Total Profit: $" . ($user_values[0] - $user_values[1]);
            }
        ?>
    </body>
</html>
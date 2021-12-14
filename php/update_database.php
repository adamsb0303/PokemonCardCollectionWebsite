<?php
    include "connect.php";
    include 'update_card_prices.php';
    include 'update_Mset_size.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $sql = "SELECT COUNT(user_id) FROM user";
    $numUsers = mysqli_fetch_array(mysqli_query($link, $sql));

    for($i = 1; $i <= $numUsers[0]; $i++){
        $sql = "UPDATE user 
                SET `user_value` =
                (SELECT SUM(market_price) FROM `collection` 
                JOIN `card` ON `card`.`card_id` = `collection`.`card_id`
                WHERE `user_id` = $i)
                WHERE `user_id` = $i";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }
?>
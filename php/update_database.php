<?php
    include "connect.php";
    include 'update_card_prices.php';
    include 'update_Mset_size.php';

    //Updates user values
    $sql = "SELECT COUNT(user_id) FROM user";
    $numUsers = mysqli_fetch_array(mysqli_query($link, $sql));

    for($i = 1; $i <= $numUsers[0]; $i++){
        $sql = "UPDATE user 
                SET `user_value` =
                (SELECT COALESCE(SUM(market_price),0) FROM `collection` 
                JOIN `card` ON `card`.`card_id` = `collection`.`card_id`
                WHERE `user_id` = $i)
                WHERE `user_id` = $i";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }

    //Backup database
    $backup_file  = "/srv/www/htdocs/PokemonCardCollectionWebsite/sql_backup/pokemon_backup_" . date('w') . ".sql";
    $result = exec("mysqldump pokemon --password='$password' --user='$user' --single-transaction > " . $backup_file);
    
    if(empty($result))
        echo "Backed up successful";
    else
        echo $result;
?>
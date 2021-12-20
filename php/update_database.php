<?php
    include "connect.php";
    include 'update_card_prices.php';

    //Update master set size
    $sql = "SELECT `set_id` FROM `set`";
    $sets = mysqli_query($link, $sql) or die(mysqli_error($link));

    while($setRow = mysqli_fetch_array($sets)){
        $setID = $setRow['set_id'];
        $sql = "UPDATE `set` SET `Mset_size` = (SELECT COUNT(`card_id`) FROM `card` WHERE `set_id` = $setID)
                WHERE `set_id` = $setID";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }

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
        echo "Backed up successfully\n";
    else
        echo $result;

    echo "Completed database update on " . date("Y/m/d") . " at " . date("h:i:s a") . "CST";
?>
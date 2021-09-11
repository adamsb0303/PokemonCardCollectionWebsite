<?php
    $sql = "SELECT `set_id` FROM `set`";
    $sets = mysqli_query($link, $sql) or die(mysqli_error($link));

    while($setRow = mysqli_fetch_array($sets)){
        $setID = $setRow['set_id'];
        $sql = "UPDATE `set` SET `Mset_size` = (SELECT COUNT(`card_id`) FROM `card` WHERE `set_id` = $setID)
                WHERE `set_id` = $setID";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }
?>
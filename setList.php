<!DOCTYPE html>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <link rel="stylesheet" href="CSS/setList.css">
        <title>Card Sets</title>
    </head>
    <body>
        <?php
            include 'php/header.php';
            include_once 'php/connect.php';

            $sql = "SELECT * FROM `set`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        ?>
            <table>
                <thead>
                    <tr>
                        <th> 

                        </th>
                        <th> 

                        </th>
                        <th <?php if($signedIn) echo 'colspan="2"';?>> 
                            Master
                        </th>
                        <th <?php if($signedIn) echo 'colspan="2"';?>>
                            Set
                        </th>
                    </tr>
                    <tr>
                        <th> 

                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Price
                        </th>
                        <?php if($signedIn)
                        echo '<th>
                            Progress
                        </th>';?>
                        <th>
                            Price
                        </th>
                        <?php if($signedIn)
                        echo '<th>
                            Progress
                        </th>' ;?>
                    </tr>
                </thead>
            <?php
            $generationIndex = 0;
            while($row = mysqli_fetch_array($result)){ 
                if($signedIn){
                    $setID = $row['set_id'];
                    $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                            JOIN `card` ON card.card_id = collection.card_id
                            JOIN `user` on user.user_id = collection.user_id
                            WHERE `collection`.`user_id` = '" . $_COOKIE["ID"] . "'
                            AND `user_key` = '" . $_COOKIE["Key"] . "'
                            AND `set_id` = $setID AND `variant_id` = 1";
                    $setResult = mysqli_query($link, $sql) or die(mysqli_error($link));
                    $setOwned = mysqli_fetch_array($setResult);
                    
                    $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                            JOIN `card` ON card.card_id = collection.card_id
                            JOIN `user` on user.user_id = collection.user_id
                            WHERE `collection`.`user_id` = '" . $_COOKIE["ID"] . "'
                            AND `user_key` = '" . $_COOKIE["Key"] . "'
                            AND `set_id` = $setID";
                    $setResult = mysqli_query($link, $sql) or die(mysqli_error($link));
                    $mSetOwned = mysqli_fetch_array($setResult);
                }
            ?>
                <tr id="<?=$row['set_name']?>">
                    <!--Name-->
                    <td style="vertical-align:middle">
                        <image src="Images/Symbols/<?=$row['set_name']?>.png" class="setImage"></image>
                    </td>
                    <td style="text-align:left;">
                        <a href="setPage.php?set=<?=$row['set_name']?>"><?=$row['set_name']?></a>
                    </td>
                    <!--M set price-->
                    <td>
                        $<?=number_format($row['Mset_price'],2)?>
                    </td> 
                    <!--M set completion-->
                    <?php if($signedIn){
                    echo '<td>' .
                        $setOwned[0] . '/' .
                        $row['Mset_size'] .
                    '</td>';
                    }?>
                    <!--Set price-->
                    <td>
                        $<?=number_format($row['set_price'],2)?>
                    </td>
                    <!--Set completion-->
                    <?php if($signedIn){
                    echo '<td>' .
                        $mSetOwned[0] . '/' .
                        $row['set_size'] .
                    '</td>';
                    }?>

                </tr>
            <?php }//close while loop?>
            </table>
        <p style="text-align:center;">
            This product uses TCGplayer data but is not endorsed or certified by TCGplayer.
        </p>
    </body>
</html>
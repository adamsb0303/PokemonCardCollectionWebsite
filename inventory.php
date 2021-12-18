<!DOCTYPE html>
<?php include 'php/sortTable_functions.php';?>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <script src="JavaScript/inventory.js"></script>
        <title>Inventory</title>
    </head>
    <body>
        <div class="overlay" id="overlay" onClick="document.getElementById('overlay').style.display = 'none';">
            <div class="root" style="background-color:white;height:75%;margin-top:6.25%;">
                <div style="display:flex; justify-content:center; align-items:center; width: 50%;">
                    <image src="https://product-images.tcgplayer.com/219058.jpg" style="height:50%;width:auto;">
                </div>
                <div style="width:50%;">
                    <div style="height:50%;">Current</div>
                    <div style="height:50%;">New</div>
                </div>
            </div>
        </div>
        <?php
            include 'php/header.php';
            include_once 'php/connect.php';
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
            $orderByParam = "SET1";
                if(!empty($_GET['sort']))
                    $orderByParam = $_GET['sort'];
            $search = "";
                if(!empty($_GET['q']))
                    $search = addslashes($_GET['q']);
            $set = [];
                if(!empty($_GET['set']))
                    $set = explode(",", $_GET['set']);

        if($signedIn){
        ?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <?php
            $pageName = 'inventory';
            include 'php/sortTable_filters.php';?>
            <div class="searchResults">
                <table style="width:100%;">
                    <thead>
                        <?php
                            //Basic query
                            $sql = "SELECT * FROM `collection`
                                    JOIN `card` ON `collection`.`card_id` = `card`.`card_id`
                                    JOIN `set` ON `card`.`set_id` = `set`.`set_id`
                                    JOIN `user` ON `collection`.`user_id` = `user`.`user_id`
                                    JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`
                                    WHERE `collection`.`user_id` = '" . $_COOKIE["ID"] . "'
                                    AND `user_key` = '" . $_COOKIE["Key"] . " '";

                            $countSQL = "SELECT COUNT(`purchase_id`) FROM `collection`
                                        JOIN `card` ON `collection`.`card_id` = `card`.`card_id`
                                        JOIN `set` ON `card`.`set_id` = `set`.`set_id`
                                        JOIN `user` ON `collection`.`user_id` = `user`.`user_id`
                                        JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`
                                        WHERE `collection`.`user_id` = '" . $_COOKIE["ID"] . " '";
                            
                            include 'php/sortTable_sortCategories.php';
                        ?>
                    </thead>
                    <tbody>
                        <?php
                            $resultQuery = mysqli_query($link, $countSQL) or die(mysqli_error($link));
                            $resultSize = mysqli_fetch_array($resultQuery);
                            $max = ceil($resultSize[0] / 50);

                            //select page
                            $sql .= "LIMIT " . ($pageNum - 1) * 50 . ", " . 50;

                            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                            while($card = mysqli_fetch_array($result)){
                                echo '<tr>';
                                    echo '<td onClick="document.getElementById(\'overlay\').style.display = \'block\';">edit</td>';
                                    echo '<td><image src="https://product-images.tcgplayer.com/' . $card['product_id'] . '.jpg" style="height: 36pt; width: auto;"></image></td>';
                                    echo '<td style="text-align:left; width: 1em"><a href="./card.php?id=' . $card['card_id'] . '">' .  $card['card_name'] . '</a></td>';
                                    echo '<td>' . $card['variant_name'] . '</td>';
                                    echo '<td>' . $card['set_name'] . '</td>';
                                    echo '<td>' . $card['set_num'] . '</td>';
                                    echo '<td>$' . $card['market_price'] . '</td>';

                                    if($card['purchase_date'])
                                        echo '<td>' . $card['purchase_date'] . '</td>';
                                    else
                                        echo '<td>-</td>';

                                    if($card['condition'])
                                        echo '<td>' . $card['condition'] . '</td>';
                                    else
                                        echo '<td>-</td>';

                                    if($card['purchase_price'])
                                        echo '<td>$' . number_format($card['market_price'] - $card['purchase_price'], 2) . '</td>';
                                    else
                                        echo '<td>-</td>';

                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
                <?php include 'php/sortTable_pageSelect.php'; ?>
            </div>
        </div>
        <?php } //end of if($signedIn) ?>
    </body>
</html>
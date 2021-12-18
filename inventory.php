<!DOCTYPE html>
<?php include 'php/sortTable_functions.php';?>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <script src="JavaScript/inventory.js"></script>
        <title>Inventory</title>
    </head>
    <body>
        <?php
            include 'php/header.php';
            include_once 'php/connect.php';

            //Table Sort Variables
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
            $overlayVal = 0;
                if(!empty($_GET['overlay']))
                    $overlayVal = $_GET['overlay'];
            
        if($signedIn){
            $sql = "SELECT * FROM `collection`
                    JOIN `card` ON `collection`.`card_id` = `card`.`card_id`
                    JOIN `set` ON `card`.`set_id` = `set`.`set_id`
                    JOIN `user` ON `collection`.`user_id` = `user`.`user_id`
                    JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`
                    WHERE `purchase_id` = " . $overlayVal . "
                    AND `collection`.`user_id` = '" . $_COOKIE["ID"] . "'
                    AND `user_key` = '" . $_COOKIE["Key"] . " '";

            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $card = mysqli_fetch_array($result);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            //delete opened entry
            if(isset($_POST['delete']) && $result->num_rows != 0){
                $sql = "DELETE FROM `collection` WHERE `purchase_id` = " . $overlayVal;
                mysqli_query($link, $sql) or die(mysqli_error($link));
                header("Location: inventory.php?" . updateQString($search, $set, $orderByParam, $pageNum));
            }

            //push form data to sql server
            if(isset($_POST['submit']) && $result->num_rows != 0){
                $cardID = $card['card_id'];
                $formValues = array($_COOKIE['ID'], $cardID);
                $queryValues = array("`user_id`", "`card_id`");

                $price = $_POST['price'] == '' ? 'NULL' : $_POST['price'];
                $date = $_POST['date'] == '' ? 'NULL' : '\'' . $_POST['date'] . '\'';
                $condition = $_POST['condition'] == '' ? 'NULL' : '\'' . $_POST['condition'] . '\'';

        
                array_push($queryValues, '`purchase_price`');
                array_push($formValues, $price);

                array_push($queryValues, '`purchase_date`');
                array_push($formValues, $date);

                array_push($queryValues, '`condition`');
                array_push($formValues, $condition);

                /*$sql = "UPDATE user
                        SET user_cost = (SELECT user_cost FROM user WHERE user_id = " . $_COOKIE['ID'] . ")+ (" . 
                        $_POST['price'] . " - (SELECT purchase_price FROM collection WHERE purchase_id = " . $overlayVal . "))" . 
                        " WHERE user_id = " . $_COOKIE['ID'];
                mysqli_query($link, $sql) or die(mysqli_error($link));*/
                

                $sql = "UPDATE `collection` SET ";
                for($i = 0; $i < count($queryValues); $i++){
                    if($i != count($queryValues) - 1)
                        $sql .= $queryValues[$i] . ' = ' . $formValues[$i] . ',';
                    else
                        $sql .= $queryValues[$i] . ' = ' . $formValues[$i];
                }
                $sql .= " WHERE `purchase_id` = " . $overlayVal;
                mysqli_query($link, $sql) or die(mysqli_error($link));
                header("Location: inventory.php?" . updateQString($search, $set, $orderByParam, $pageNum));
            }
        ?>
        <?php
            if($result->num_rows == 0)
                echo '<div class="overlay" style="display:none;" id="overlay">';
            else
                echo '<div class="overlay" style="display:block;" id="overlay">';
        ?>
            <div class="root" style="background-color:white;height:75%;margin-top:6.25%;">
                <div style="display:flex; justify-content:center; align-items:center; width: 50%;">
                    <image src="https://product-images.tcgplayer.com/<?=$card['product_id']?>.jpg" style="height:50%;width:auto;">
                </div>
                <div style="width:50%;">
                    <text style="float:right; padding-top: 8px; cursor:pointer; font-size:16pt;" onClick="window.location.href='inventory.php?<?=updateQString($search, $set, $orderByParam, $pageNum)?>';">X</text>
                    <!--New Collection Values-->
                    <div style="height:100%; display:flex; align-items:center;">
                        <form method="post">
                            <br><text>Edit Collection Entry: </text><br/>
                            <!--Price Purchased-->
                            <text>Price Purchased: </text>
                            <input type="number" min="0" step="0.01" placeholder = "NULL" name="price" value="<?=$card['purchase_price']?>"/>
                            <!--<button title="Calculates price based off potential pulls">From Pack</button>-->
                            <br/>
                            <!--Date Purchased-->
                            <text>Date Purchased: </text>
                            <input type="date" name="date" value="<?=$card['purchase_date']?>"></input>
                            <br/>
                            <!--Condition-->
                            <text>Condition: </text>
                            <select name="condition">
                                    <option></option>
                                    <option value="NM" <?php if($card['condition'] == 'NM') echo 'selected';?>>NM</option>
                                    <option value="LP" <?php if($card['condition'] == 'LP') echo 'selected';?>>LP</option>
                                    <option value="MP" <?php if($card['condition'] == 'MP') echo 'selected';?>>MP</option>
                                    <option value="HP" <?php if($card['condition'] == 'HP') echo 'selected';?>>HP</option>
                                    <option value="DMG" <?php if($card['condition'] == 'DMG') echo 'selected';?>>DMG</option>
                                </select>
                            <br/>
                            <!--Submit-->
                            <input type="submit" name="submit" value="Submit"/>
                            <!--Remove-->
                            <input style="margin-left: 25%;" type="submit" name="delete" value="Remove"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                                    echo '<td><button onClick="window.location.href=\'inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum) . '&overlay=' . $card['purchase_id'] . '\';">Edit</button></td>';
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
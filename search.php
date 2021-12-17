<?php include 'php/sortTable_functions.php';?>
<html>
    <head>
        <?php include_once "php/head.php" ?>
        <title>Search</title>
    </head>
    <body>
        <?php
            include "php/header.php";
            include_once "php/connect.php";
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
            $orderByParam = "PRI2";
                if(!empty($_GET['sort']))
                    $orderByParam = $_GET['sort'];
            $search = "";
                if(!empty($_GET['q']))
                    $search = addslashes($_GET['q']);
            $set = [];
                if(!empty($_GET['set']))
                    $set = explode(",", $_GET['set']);
        ?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <?php
            $pageName = 'search';
            include 'php/sortTable_filters.php'; ?>
            <div class="searchResults">
                <table style="table-layout:fixed; width:100%;">
                    <thead>
                        <?php
                            //Basic query
                            $sql = "SELECT * FROM `card` 
                                    JOIN `variant` ON card.variant_id = variant.variant_id
                                    JOIN `set` ON card.set_id = set.set_id ";

                            $countSQL = "SELECT COUNT(`card_id`) FROM `card` 
                                        JOIN `variant` ON card.variant_id = variant.variant_id
                                        JOIN `set` ON card.set_id = set.set_id ";
                            
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
                                    echo '<td><input type="checkbox"></td>';
                                    echo '<td><image src="https://product-images.tcgplayer.com/' . $card['product_id'] . '.jpg" style="height: 36pt; width: auto;"></image></td>';
                                    echo '<td style="text-align:left; width: 1em"><a href="./card.php?id=' . $card['card_id'] . '">' .  $card['card_name'] . '</a></td>';
                                    echo '<td>' . $card['variant_name'] . '</td>';
                                    echo '<td>' . $card['set_name'] . '</td>';
                                    echo '<td>' . $card['set_num'] . '</td>';
                                    echo '<td>$' . $card['market_price'] . '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
                <?php include 'php/sortTable_pageSelect.php'; ?>
            </div>
        </div>
    </body>
</html>
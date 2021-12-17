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
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
            $orderByParam = "SET2";
                if(!empty($_GET['sort']))
                    $orderByParam = $_GET['sort'];
            $search = "";
                if(!empty($_GET['q']))
                    $search = $_GET['q'];
            $set = [];
                if(!empty($_GET['set']))
                    $set = explode(",", $_GET['set']);

        if($signedIn){
        ?>
        <div class="root">
            <?php
            $pageName = 'inventory';
            include 'php/sortTable_filters.php';?>
            <div class="searchResults">
                <table>
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

                            if($search != "" || !empty($set)){
                                $sql .= "AND ";
                                $countSQL .= "AND ";
                            }

                            //word search
                            if($search != ""){
                                $sql .= "`card_name` LIKE '%" . $search . "%'";
                                $countSQL .= "`card_name` LIKE '%" . $search . "%'";
                                if(!empty($set)){
                                    $sql .= " AND (";
                                    $countSQL .= " AND (";
                                }
                            }

                            //restrict set
                            for($i = 0; $i < count($set); $i++){
                                if($i == 0){
                                    $sql .= "`set_name` = '" . $set[$i] . "' ";
                                    $countSQL .= "`set_name` = '" . $set[$i] . "' ";
                                }else{
                                    $sql .= "OR `set_name` = '" . $set[$i] . "' ";
                                    $countSQL .= "OR `set_name` = '" . $set[$i] . "' ";
                                }

                                if($i == count($set) - 1 && $search != ""){
                                    $sql .= ") ";
                                    $countSQL .= ") ";
                                }
                            }
                            
                            //select order
                            $sortCategory = substr($orderByParam,0,3);
                            $sortNum = substr($orderByParam, 3);

                            $sortDirection = ($sortNum == 1) ? "ASC" : "DESC";
                            $sortCategory = ($sortNum == 1 || $sortNum == 2) ? $sortCategory : "";
                            
                            //card image
                            echo '<th style="width:10%;"></th>';

                            //Sort Name
                            if($sortCategory == "NAM"){
                                $sql .= "ORDER BY `card_name` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "NAM" . ($sortNum % 3 + 1), $pageNum) . '">Name</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "NAM1", $pageNum) . '">Name</a></th>';

                            //Sort Variant
                            if($sortCategory == "VAR"){
                                $sql .= "ORDER BY `variant_name` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "VAR" . ($sortNum % 3 + 1), $pageNum) . '">Variant</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "VAR1", $pageNum) . '">Variant</a></th>';

                            //Sort Set
                            if($sortCategory == "SET"){
                                $sql .= "ORDER BY `set_name` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "SET" . ($sortNum % 3 + 1), $pageNum) . '">Set</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "SET1", $pageNum) . '">Set</a></th>';

                            //Sort Set Number
                            if($sortCategory == "NUM"){
                                $sql .= "ORDER BY cast(`set_num` as unsigned) " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "NUM" . ($sortNum % 3 + 1), $pageNum) . '">#</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "NUM1", $pageNum) . '">#</a></th>';

                            //Sort Price
                            if($sortCategory == "PRI"){
                                $sql .= "ORDER BY `market_price` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "PRI" . ($sortNum % 3 + 1), $pageNum) . '">Current Market Price</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                      </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "PRI1", $pageNum) . '">Current Market Price</a></th>';

                            //Sort Purchase Date
                            if($sortCategory == "DAT"){
                                $sql .= "ORDER BY `purchase_date` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "DAT" . ($sortNum % 3 + 1), $pageNum) . '">Purchase Date</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                        </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "DAT1", $pageNum) . '">Purchase Date</a></th>';

                            //Sort Condition
                            if($sortCategory == "CON"){
                                $sql .= "ORDER BY `condition` " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "CON" . ($sortNum % 3 + 1), $pageNum) . '">Condition</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                        </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "CON1", $pageNum) . '">Condition</a></th>';

                            //Sort Current Gain
                            if($sortCategory == "PRF"){
                                $sql .= "ORDER BY (`market_price` - `purchase_price`) " . $sortDirection . ", `card`.`card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="inventory.php?' . updateQString($search, $set, "PRF" . ($sortNum % 3 + 1), $pageNum) . '">Gain</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                        </th>';
                            }else
                                echo '<th><a href="inventory.php?' . updateQString($search, $set, "PRF1", $pageNum) . '">Gain</a></th>';
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
                <div class="pageSelect">
                    <?php
                        if($pageNum == 1)
                            echo '<a style="color:grey">ᐸ</a>';
                        else
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">ᐸ</a>';

                        if($max <= 5){
                            for($i = 1; $i <= $max; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                        }else if($pageNum <= 4){
                            for($i = 1; $i <= 5; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                            echo '<a>...</a>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
                        }else if($pageNum > $max - 4){
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
                            echo '<a>...</a>';
                            for($i = $max - 4; $i <= $max; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                        }else{
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
                            if($pageNum - 2 != 1)
                                echo '<a>...</a>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum - 2) . '">' . ($pageNum - 2) . '</a>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">' . ($pageNum - 1) . '</a>';
                            echo '<strong>' . $pageNum . '</strong>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">' . ($pageNum + 1) . '</a>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum + 2) . '">' . ($pageNum + 2) . '</a>';
                            if($pageNum + 2 != $max)
                                echo '<a>...</a>';
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
                        }
                        if($pageNum == $max)
                            echo '<a style="color:grey">ᐳ</a>';
                        else
                            echo '<a href="inventory.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">ᐳ</a>';
                    ?>
                </div>
            </div>
        </div>
        <?php } //end of if($signedIn) ?>
    </body>
</html>
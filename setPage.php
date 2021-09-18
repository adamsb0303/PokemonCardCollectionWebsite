<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/setPage.css">
        <script src="JavaScript/setPage.js"></script>
        <?php
            $name = $_GET['set'];
            echo '<title>' . $name . '</title>';
        ?>
    </head>
    <body>
        <?php
            include_once 'php/connect.php';
            $sql = "SELECT * FROM `card` INNER JOIN `variant` ON card.variant_id = variant.variant_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')";

            $variant_result = mysqli_query($link, $sql) or die(mysqli_error($link));

            $cardAmount = 0;

            $cardTable = '<table>' .
                '<th>' .
                    'Set Num' .
                '</th>' .
                '<th style="text-align:left;">' .
                    'Name' .
                '</th>' .
                '<th>' .
                    'Market Price' .
                '</th>' .
                '<th>' .
                    'Average Price' .
                '</th>' .
                '<th>' .
                    'Own?' .
                '</th>';
            while($variant = mysqli_fetch_array($variant_result)){
                $cardAmount++;
                if($variant['variant_name'] !== 'Normal'){
                    $cardTable .= '<tr class="variantRow">' .
                        //Set Num
                        '<td>' .
                        '</td>' .
                        //Card Name
                        '<td>' .
                            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;∟&nbsp;&nbsp;' . $variant['variant_name'] .
                        '</td>';
                } else{
                    $cardTable .= '<tr class="cardRow">' .
                        //Set Num
                        '<td>' .
                            $variant['set_num'] .
                        '</td>' .
                        //Card Name
                        '<td>' .
                            '<a href="https://www.tcgplayer.com/product/' . $variant['product_id'] . '">' . $variant['card_name'] . '</a>' .
                        '</td>';
                }
                //Market Price
                $cardTable .= '<td>';
                    if($variant['market_price'] != 0)
                        $cardTable .= '<text id="MP_' . $variant['card_id'] . '">$' . $variant['market_price'] . '</text>';
                    else
                        $cardTable .= '<text id="MP_' . $variant['card_id'] . '">-</text>';
                $cardTable .= '</td>' .
                //Average Price
                '<td>';
                    if($variant['average_price'] != 0)
                        $cardTable .= '<text id="AV_' . $variant['card_id'] . '">$' . $variant['average_price'] . '</text>';
                    else
                        $cardTable .= '<text id="AV_' . $variant['card_id'] . '">-</text>'; 
                $cardTable .= '</td>' .
                //# in Inventory
                '<td id="Number in Inventory">' .
                    '<button onclick="window.open(\'inventory.php?id=' . $variant['card_id'] . '\')">Enter</button>' .
                    //'<input type="checkbox" onclick="hidePrice(' . $variant['card_id'] . ',' . $variant['variant_id'] . ')" id="INV_' . $variant['card_id']. '">' .
                '</td>' .
            '</tr>';
            }
            $cardTable .= '</table>';

            $sql = "SELECT SUM(`market_price`) FROM `collection`
                    JOIN `card` ON collection.card_id = card.card_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')
                    AND `variant_id` = 1";
            $userInfoResult = mysqli_query($link, $sql) or die(mysqli_error($link));
            $sumMarketPrice = mysqli_fetch_array($userInfoResult);

            $sql = "SELECT SUM(`market_price`) FROM `collection`
                    JOIN `card` ON collection.card_id = card.card_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')";
            $userInfoResult = mysqli_query($link, $sql) or die(mysqli_error($link));
            $mSumMarketPrice = mysqli_fetch_array($userInfoResult);

            $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                    JOIN `card` ON collection.card_id = card.card_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')
                    AND `variant_id` = 1";
            $userInfoResult = mysqli_query($link, $sql) or die(mysqli_error($link));
            $cardsOwned = mysqli_fetch_array($userInfoResult);

            $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                    JOIN `card` ON collection.card_id = card.card_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')";
            $userInfoResult = mysqli_query($link, $sql) or die(mysqli_error($link));
            $mCardsOwned = mysqli_fetch_array($userInfoResult);

            echo '<div class="header">';
                $sql = "SELECT * FROM `set` WHERE set_name = '$name'";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $row = mysqli_fetch_array($result);
                echo '<img src="Images/Logos/' . $row['set_name'] . '.png" alt="' . $row['set_name'] . ' Set Symbol" style="width:25vw; height:auto;">' . '<br />';
                echo $name . '<br />';
                echo 'Set Size: ' . $row['set_size'] . '<br/>';
                echo 'Set Price: $' . number_format($row['set_price'], 2) . '<br />';
                echo 'Master Set Size: ' . $cardAmount . '<br/>';
                echo 'Master Set Price: $' . number_format($row['Mset_price'], 2) . '<br />';
                echo '<br/>';
                echo 'Set Remainder: <text id="size">' . $row['set_size'] - $cardsOwned[0] . '</text><br />';
                echo 'Set Market Price: $<text id="setPrice">' . number_format($row['set_price'] - $sumMarketPrice[0], 2) . '</text><br />';
                echo 'Master Set Remainder: <text id="mSize">' . $cardAmount - $mCardsOwned[0] . '</text><br />';
                echo 'Master Set Market Price: $<text id="mSetPrice">' . number_format($row['Mset_price'] - $mSumMarketPrice[0], 2) . '</text><br />';
            echo '</div><br/>';

            echo $cardTable;
        ?>
    </body>
</html>
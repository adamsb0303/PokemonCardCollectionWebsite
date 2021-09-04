<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/setPage.css">
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
            $marketPriceSum = 0;
            $averagePriceSum = 0;

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
                    'Price Purchased' .
                '</th>' .
                '<th>' .
                    'Date Purchased' .
                '</th>' .
                '<th>' .
                    'Condition' .
                '</th>' .
                '<th>' .
                    '# in Inventory' .
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
                    if($variant['market_price'] != 0){
                        $cardTable .= '<text id="MP_' . $variant['card_id'] . '">$' . $variant['market_price'] . '</text>';
                        $marketPriceSum += $variant['market_price'];
                    }else
                        $cardTable .= '<text id="MP_' . $variant['card_id'] . '">-</text>';
                $cardTable .= '</td>' .
                //Average Price
                '<td>';
                    if($variant['average_price'] != 0){
                        $cardTable .= '<text id="AV_' . $variant['card_id'] . '">$' . $variant['average_price'] . '</text>';
                        $averagePriceSum += $variant['average_price'];
                    }else
                        $cardTable .= '<text id="AV_' . $variant['card_id'] . '">-</text>'; 
                $cardTable .= '</td>' .
                //Price Purchased
                '<td id="Price Purchased">' .
                    '<input id="PP_' . $variant['card_id'] . '" type="number" min="0" step="0.01" placeholder = "$0.00"/>' .
                    '<button title="Calculates price based off potential pulls" type="button">From Pack</button>' .
                '</td>' .
                //Date Purchased
                '<td id="Date Purchased">' .
                    '<input id="DP_' . $variant['card_id'] . '" type="date"></input>' .
                '</td>' .
                //Condition
                '<td id="Date Purchased">' .
                    '<select id="CND_' . $variant['card_id'] . '">' .
                        '<option value="Near Mint">NM</option>' .
                        '<option value="Light Played">LP</option>' .
                        '<option value="Medium Played">MP</option>' .
                        '<option value="Heavy Played">HP</option>' .
                        '<option value="Damaged">DMG</option>' .
                    '</select>' .
                '</td>' .
                //# in Inventory
                '<td id="Number in Inventory">' .
                    '<text id="INV_' . $variant['card_id'] . '">0</text>' .
                '</td>' .
                //Submit Button
                '<td>' .
                    '<button type="submit" onclick="addToInventory(' . $variant['card_id'] . ')">Add to Inventory</button>' .
                '</td>' .
            '</tr>';
            }
            $cardTable .= '</table>';

            echo '<div class="header">';
                $sql = "SELECT * FROM `set` WHERE set_name = '$name'";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $row = mysqli_fetch_array($result);
                echo '<img src="Images/Logos/' . $row['set_name'] . '.png" alt="' . $row['set_name'] . ' Set Symbol" style="width:25vw; height:auto;">' . '<br />';
                echo $name . '<br />';
                echo "Set Size: " . $row['set_size'] . '<br />';
                echo "Master Set Size: " . $cardAmount . '<br />';
                echo "Master Set Market Price: $" . number_format($marketPriceSum, 2) . '<br />';
                echo "Progress: ";
            echo '</div><br/>';

            echo $cardTable;
        ?>
    </body>
</html>
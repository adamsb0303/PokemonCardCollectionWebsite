<!DOCTYPE html>
<html>
    <head>
        <script src="setPage.js"></script>
        <link rel="stylesheet" href="setPage.css">
    </head>
    <body>
        <div class="header">
            <?php
                include_once 'php/connect.php';
                $name = $_GET['set'];

                $sql = "SELECT * FROM `set` WHERE set_name = '$name'";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $row = mysqli_fetch_array($result);
                echo '<img src="Card_Sets/Logos/' . $row['set_name'] . '.png" alt="' . $row['set_name'] . ' Set Symbol" style="width:25vw; height:auto;">' . '<br />';
                echo $name . '<br />';
                echo "Set Size: " . $row['set_size'] . '<br />';
                echo "Progress: ";
            ?>
        </div>

        <br />

        <?php
            $name = $_GET['set'];

            $sql = "SELECT * FROM `card` INNER JOIN `variant` ON card.variant_id = variant.variant_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')";

            $variant_result = mysqli_query($link, $sql) or die(mysqli_error($link));

            $marketPriceSum = 0;
            $averagePriceSum = 0;

            echo '<table>';
                echo '<th>';
                    echo 'Set Num';
                echo '</th>';
                echo '<th style="text-align:left;">';
                    echo 'Name';
                echo '</th>';
                echo '<th>';
                    echo 'Market Price';
                echo '</th>';
                echo '<th>';
                    echo 'Average Price';
                echo '</th>';
                echo '<th>';
                    echo 'Price Purchased';
                echo '</th>';
                echo '<th>';
                    echo 'Date Purchased';
                echo '</th>';
                echo '<th>';
                    echo 'Condition';
                echo '</th>';
                echo '<th>';
                    echo '# in Inventory';
                echo '</th>';
            while($variant = mysqli_fetch_array($variant_result)){
                if($variant['variant_name'] !== 'Normal'){
                    echo '<tr class="variantRow">';
                        //Set Num
                        echo '<td>';
                        echo '</td>';
                        //Card Name
                        echo '<td>';
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;∟&nbsp;&nbsp;' . $variant['variant_name'];
                        echo '</td>';
                } else{
                    echo '<tr class="cardRow">';
                        //Set Num
                        echo '<td>';
                            echo $variant['set_num'];
                        echo '</td>';
                        //Card Name
                        echo '<td>';
                            echo '<a href="https://www.tcgplayer.com/product/' . $variant['product_id'] . '">' . $variant['card_name'] . '</a>';
                        echo '</td>';
                }
                //Market Price
                echo '<td>';
                    if($variant['market_price'] != 0){
                        echo '<text id="MP_' . $variant['card_id'] . '">$' . $variant['market_price'] . '</text>';
                        $marketPriceSum += $variant['market_price'];
                    }else
                        echo '<text id="MP_' . $variant['card_id'] . '">-</text>';
                echo '</td>';
                //Average Price
                echo '<td>';
                    if($variant['average_price'] != 0){
                        echo '<text id="AV_' . $variant['card_id'] . '">$' . $variant['average_price'] . '</text>';
                        $averagePriceSum += $variant['average_price'];
                    }else
                        echo '<text id="AV_' . $variant['card_id'] . '">-</text>'; 
                echo '</td>';
                //Price Purchased
                echo '<td id="Price Purchased">';
                    echo '<input id="PP_' . $variant['card_id'] . '" type="number" min="0" step="0.01" placeholder = "$0.00"/>';
                    echo '<button title="Calculates price based off potential pulls" type="button">From Pack</button>';
                echo '</td>';
                //Date Purchased
                echo '<td id="Date Purchased">';
                    echo '<input id="DP_' . $variant['card_id'] . '" type="date"></input>';
                echo '</td>';
                //Condition
                echo '<td id="Date Purchased">';
                    echo '<select id="CND_' . $variant['card_id'] . '">';
                        echo '<option value="Near Mint">NM</option>';
                        echo '<option value="Light Played">LP</option>';
                        echo '<option value="Medium Played">MP</option>';
                        echo '<option value="Heavy Played">HP</option>';
                        echo '<option value="Damaged">DMG</option>';
                    echo '</select>';
                echo '</td>';
                //# in Inventory
                echo '<td id="Number in Inventory">';
                    echo '<text id="INV_' . $variant['card_id'] . '">0</text>';
                echo '</td>';
                //Submit Button
                echo '<td>';
                    echo '<button type="submit" onclick="addToInventory(' . $variant['card_id'] . ')">Add to Inventory</button>';
                echo '</td>';
            echo '</tr>';
            }
            echo '<tr class="cardRow">';
                echo '<td>';
                echo '</td>';
                echo '<td style="text-align:left;">';
                echo '</td>';
                echo '<td>';
                    echo "$" . number_format($marketPriceSum, 2);
                echo '</td>';
                echo '<td>';
                    echo "$" . number_format($averagePriceSum, 2);
                echo '</td>';
                echo '<td>';
                echo '</td>';
                echo '<td>';
                echo '</td>';
                echo '<td>';
                echo '</td>';
                echo '<td>';
                echo '</td>';
            echo '</tr>';
            echo '</table>';
        ?>
    </body>
</html>
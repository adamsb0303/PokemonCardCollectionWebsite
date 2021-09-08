<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/inventory.css">
        <title>Inventory</title>
    </head>
    <body>
        <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>
        <?php
            include_once 'php/connect.php';
            if(isset($_POST['submit'])){
                $userID = 1;
                $cardID = $_POST['cardID'];
                $condition = $_POST['condition'];
                $purchasePrice = $_POST['price'];
                $purchaseDate = $_POST['date'];
                
                $sql = "INSERT INTO `collection` (`user_id`, `card_id`, `condition`, `purchase_price`, `purchase_date`) VALUES ($userID, $cardID, '$condition', $purchasePrice, '$purchaseDate');";
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }
            
            echo '<form method="post">' .
                //Card ID
                '<text>Card ID: </text>' .
                '<input name="cardID"/>' .
                '<br/>' .
                //Price Purchased
                '<text>Price Purchased: </text>' .
                '<input type="number" min="0" step="0.01" placeholder = "$0.00" name="price"/>' .
                '<button title="Calculates price based off potential pulls">From Pack</button>' .
                '<br/>' .
                //Date Purchased
                '<text>Date Purchased: </text>' .
                '<input type="date" name="date"></input>' .
                '<br/>' .
                //Condition
                '<text>Condition: </text>' .
                '<select name="condition">' .
                        '<option value="NM">NM</option>' .
                        '<option value="LP">LP</option>' .
                        '<option value="MP">MP</option>' .
                        '<option value="HP">HP</option>' .
                        '<option value="DMG">DMG</option>' .
                    '</select>' .
                '<br/>' .
                //Submit
                '<input type="submit" name="submit" value="Submit"/>' .
                '<br/><br/>' .
            '</form>';

            $totalGain = 0;
            $invTable = '';

            $sql = "SELECT * FROM `collection`
                    JOIN `card` ON `collection`.`card_id` = `card`.`card_id`
                    JOIN `set` ON `card`.`set_id` = `set`.`set_id`
                    JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $invTable .= '<table>' .
                '<th>' .
                    'Card Name' .
                '</th>' .
                '<th>' .
                    'Variant' .
                '</th>' .
                '<th>' .
                    'Set' .
                '</th>' .
                '<th>' .
                    'Set #' .
                '</th>' .
                '<th>' .
                    'Current Market Price' .
                '</th>' .
                '<th>' .
                    'Puchase Price' .
                '</th>' .
                '<th>' .
                    'Purchase Date' .
                '</th>' .
                '<th>' .
                    'Condition' .
                '</th>' .
                '<th>' .
                    'Current Gain' .
                '</th>';
            while($row = mysqli_fetch_array($result)){
                $invTable .= '<tr>' .
                    //Card Name
                    '<td>' .
                        $row['card_name'] .
                    '</td>' .
                    //Card Variant
                    '<td>' .
                        $row['variant_name'] .
                    '</td>' .
                    //Set
                    '<td>' .
                        $row['set_name'] .
                    '</td>' .
                    //Set Num
                    '<td>' .
                        $row['set_num'] .
                    '</td>' .
                    //Current Market Price
                    '<td>' .
                        '$' . $row['market_price'] .
                    '</td>' .
                    //Purchase Price
                    '<td>' .
                        '$' . $row['purchase_price'] .
                    '</td>' .
                    //Purchase Date
                    '<td>' .
                        $row['purchase_date'] .
                    '</td>' .
                    //Condition
                    '<td>' .
                        $row['condition'] .
                    '</td>' .
                    //Current Gain
                    '<td>';
                        $totalGain += $row['market_price'] - $row['purchase_price'];
                        $invTable .= '$' . $row['market_price'] - $row['purchase_price'] .
                    '</td>' .
                '</tr>';
            }
            $invTable .= '</table>';

            echo '$' . number_format($totalGain, 2);
            echo $invTable;
        ?>
    </body>
</html>
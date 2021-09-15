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
                $formValues = array(1, $_POST['cardID']);
                //$userID = 1;
                //$cardID = $_POST['cardID'];
                $condition =  NULL;
                $purchasePrice = NULL;
                $purchaseDate = NULL;

                $queryValues = "`user_id`, `card_id`";

                if($_POST['condition'] != ''){
                    $queryValues .= ', `condition`';
                    array_push($formValues, '\'' . $_POST['condition'] . '\'');
                }
                if($_POST['price'] != ''){
                    $queryValues .= ', `purchase_price`';
                    array_push($formValues, $_POST['price']);
                }
                if($_POST['date'] != ''){
                    $queryValues .= ', `purchase_date`';
                    array_push($formValues, '\'' . $_POST['date'] . '\'');
                }

                $sql = "INSERT INTO `collection` ($queryValues) VALUES (" . implode(", ", $formValues) . ");";
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }

            $cardIDQString = "";
            if(!empty($_GET['id']))
                $cardIDQString = $_GET['id'];
            
            echo '<form method="post">' .
                //Card ID
                '<text>Card ID: </text>' .
                '<input name="cardID" value="' . $cardIDQString . '"/>' .
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
                        '<option></option>' .
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
                    JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`
                    ORDER BY `purchase_id` DESC";
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
                    '<td>';
                        if($row['purchase_price'] != '')
                            $invTable .= '$' . $row['purchase_price'];
                        else
                            $invTable .= '-';
                    $invTable .= '</td>' .
                    //Purchase Date
                    '<td>';
                        if($row['purchase_date'] != '')
                            $invTable .= $row['purchase_date'];
                        else
                            $invTable .= '-';
                    $invTable .= '</td>' .
                    '</td>' .
                    //Condition
                    '<td>';
                        if($row['condition'] != '')
                            $invTable .= $row['condition'];
                        else
                            $invTable .= '-';
                    $invTable .= '</td>' .
                    '</td>' .
                    //Current Gain
                    '<td>';
                        if($row['purchase_price'] != ""){
                            $totalGain += $row['market_price'] - $row['purchase_price'];
                            $invTable .= '$' . $row['market_price'] - $row['purchase_price'];
                        }else
                            $invTable .= '-';
                    '</td>' .
                '</tr>';
            }
            $invTable .= '</table>';

            echo '$' . number_format($totalGain, 2);
            echo $invTable;
        ?>
    </body>
</html>
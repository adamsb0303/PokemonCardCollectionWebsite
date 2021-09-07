<!DOCTYPE html>
<html>
    <head>
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
                $cardID = rand(1, 26329);
                $condition = 'LP';
                $purchasePrice = 0.75;
                $purchaseDate = '2021-09-06';
                
                $sql = "INSERT INTO `collection` (`user_id`, `card_id`, `condition`, `purchase_price`, `purchase_date`) VALUES ($userID, $cardID, 'LP', $purchasePrice, '$purchaseDate');";
                mysqli_query($link, $sql) or die(mysqli_error($link));
            }
            
            echo '<form method="post">';
                //Price Purchased
                echo '<text>Price Purchased: </text>';
                echo '<input type="number" min="0" step="0.01" placeholder = "$0.00"/>';
                echo '<button title="Calculates price based off potential pulls">From Pack</button>';
                echo '<br/>';
                //Date Purchased
                echo '<text>Date Purchased: </text>';
                echo '<input type="date"></input>';
                echo '<br/>';
                //Condition
                echo '<text>Condition: </text>';
                echo '<select>' .
                        '<option value="Near Mint">NM</option>' .
                        '<option value="Light Played">LP</option>' .
                        '<option value="Medium Played">MP</option>' .
                        '<option value="Heavy Played">HP</option>' .
                        '<option value="Damaged">DMG</option>' .
                    '</select>';
                echo '<br/>';
                //Submit
                echo '<input type="submit" name="submit" value="Submit"/>';
                echo '<br/><br/>';
            echo '</form>';

            $sql = "SELECT * FROM `collection`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            echo '<table>';
                echo '<th>';
                    echo 'Card ID';
                echo '</th>';
                echo '<th>';
                    echo 'Condition';
                echo '</th>';
                echo '<th>';
                    echo 'Puchase Price';
                echo '</th>';
                echo '<th>';
                    echo 'Purchase Date';
                echo '</th>';
            while($row = mysqli_fetch_array($result)){
                echo '<tr>';
                    //Card ID
                    echo '<td>';
                        echo $row['card_id'];
                    echo '</td>';
                    //Condition
                    echo '<td>';
                        echo $row['condition'];
                    echo '</td>';
                    //Purchase Price
                    echo '<td>';
                        echo $row['purchase_price'];
                    echo '</td>';
                    //Purchase Date
                    echo '<td>';
                        echo $row['purchase_date'];
                    echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        ?>
    </body>
</html>
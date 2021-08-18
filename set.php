<!DOCTYPE html>
<html>
    <head>
        <!--<script src="Card Collection Script.js"></script>
        <link rel="stylesheet" href="Card Collection StyleSheet.css">-->
    </head>
    <body>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td>
                    <h1 style="border: 2px solid black; border-radius: 5px;">Testing Testing Testing</h1>
                </td>
            </tr>
        </table>


        <?php
            $name = $_GET['set'];
            include_once 'connect.php';

            $sql = "SELECT * FROM `card` INNER JOIN `variant` ON card.variant_id = variant.variant_id
                    WHERE `set_id` = (SELECT `set_id` FROM `set` WHERE set_name = '$name')";

            $variant_result = mysqli_query($link, $sql) or die(mysqli_error($link));

            echo '<table style="margin-left:auto; margin-right:auto">';
                echo '<th>';
                    echo 'Set Num';
                echo '</th>';
                echo '<th>';
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
                    echo '# in Inventory';
                echo '</th>';
                echo '<th>';
                echo '</th>';
            while($variant = mysqli_fetch_array($variant_result)){
                if($variant['variant_name'] !== 'Normal'){
                    echo '<tr>';
                        //Set Num
                        echo '<td>';
                        echo '</td>';
                        //Card Name
                        echo '<td>';
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $variant['variant_name'];
                        echo '</td>';
                        //Market Price
                        echo '<td>';
                        echo '</td>';
                        //Average Price
                        echo '<td>';
                        echo '</td>';
                        //Price Purchased
                        echo '<td>';
                        echo '</td>';
                        //Date Purchased
                        echo '<td>';
                            echo '<input type="date"></input>';
                        echo '</td>';
                        //# in Inventory
                        echo '<td>';
                        echo '</td>';
                    echo '</tr>';
                } else{
                    echo '<tr>';
                        //Set Num
                        echo '<td>';
                            echo $variant['set_num'];
                        echo '</td>';
                        //Card Name
                        echo '<td>';
                            echo $variant['card_name'];
                        echo '</td>';
                        //Market Price
                        echo '<td>';
                        echo '</td>';
                        //Average Price
                        echo '<td>';
                        echo '</td>';
                        //Price Purchased
                        echo '<td>';
                        echo '</td>';
                        //Date Purchased
                        echo '<td>';
                            echo '<input type="date"></input>';
                        echo '</td>';
                        //# in Inventory
                        echo '<td>';
                        echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
        ?>
    </body>
</html>
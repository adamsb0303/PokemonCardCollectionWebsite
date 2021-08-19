<!DOCTYPE html>
<html>
    <head>
        <!--<script src="Card Collection Script.js"></script>-->
        <link rel="stylesheet" href="setPage.css">
    </head>
    <body>
        <div class="header" style="">
            <?php
                include_once 'connect.php';
                $name = $_GET['set'];

                $sql = "SELECT * FROM `set` WHERE set_name = '$name'";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                $row = mysqli_fetch_array($result);
                echo '<img src="Card_Sets/Logos/' . $row['set_name'] . '.png" alt="' . $row['set_name'] . ' Set Symbol">' . '<br />';
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
                        //Market Price
                        echo '<td>';
                            echo '$99999.99';
                        echo '</td>';
                        //Average Price
                        echo '<td>';
                            echo '$99999.99';
                        echo '</td>';
                        //Price Purchased
                        echo '<td>';
                            echo '$99999.99';
                        echo '</td>';
                        //Date Purchased
                        echo '<td>';
                            echo '<input type="date"></input>';
                        echo '</td>';
                        //# in Inventory
                        echo '<td>';
                            echo '9999999';
                        echo '</td>';
                        //Submit Button
                        echo '<td>';
                            echo '<button>Submit</button>';
                        echo '</td>';
                    echo '</tr>';
                } else{
                    echo '<tr class="cardRow">';
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
                            echo '$99999.99';
                        echo '</td>';
                        //Average Price
                        echo '<td>';
                            echo '$99999.99';
                        echo '</td>';
                        //Price Purchased
                        echo '<td>';
                            echo '$99999.99';
                        echo '</td>';
                        //Date Purchased
                        echo '<td>';
                            echo '<input type="date"></input>';
                        echo '</td>';
                        //# in Inventory
                        echo '<td>';
                            echo '9999999';
                        echo '</td>';
                        //Submit Button
                        echo '<td>';
                            echo '<button>Submit</button>';
                        echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
        ?>
    </body>
</html>
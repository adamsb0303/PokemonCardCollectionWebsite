<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/inventory.css">
        <link rel="stylesheet" href="CSS/index.css">
        <script src="JavaScript/inventory.js"></script>
        <title>Inventory</title>
    </head>
    <body>
        <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>
        <?php
            include 'php/header.php';
            include_once 'php/connect.php';

            $sql = "SELECT * FROM `collection`
                    JOIN `card` ON `collection`.`card_id` = `card`.`card_id`
                    JOIN `set` ON `card`.`set_id` = `set`.`set_id`
                    JOIN `variant` ON `variant`.`variant_id` = `card`.`variant_id`
                    ORDER BY `card`.`set_id`, `card`.`card_id`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        ?>
        <div class="root">
            <table>
                <th>
                    Card Name
                </th>
                <th>
                    Variant
                </th>
                <th>
                    Set
                </th>
                <th>
                    Set #
                </th>
                <th>
                    Current Market Price
                </th>
                <th>
                    Puchase Price
                </th>
                <th>
                    Purchase Date
                </th>
                <th>
                    Condition
                </th>
                <th>
                    Current Gain
                </th>
        <?php
            while($row = mysqli_fetch_array($result)){
                echo '<tr>' .
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
                            echo '$' . $row['purchase_price'];
                        else
                            echo '-';
                    echo '</td>' .
                    //Purchase Date
                    '<td>';
                        if($row['purchase_date'] != '')
                            echo $row['purchase_date'];
                        else
                            echo '-';
                    echo '</td>' .
                    '</td>' .
                    //Condition
                    '<td>';
                        if($row['condition'] != '')
                            echo $row['condition'];
                        else
                            echo '-';
                    echo '</td>' .
                    '</td>' .
                    //Current Gain
                    '<td>';
                        if($row['purchase_price'] != ""){
                            echo '$' . number_format($row['market_price'] - $row['purchase_price'],2);
                        }else
                            echo '-';
                    '</td>' .
                '</tr>';
            }
            echo '</table>';
        ?>
        </div>
    </body>
</html>
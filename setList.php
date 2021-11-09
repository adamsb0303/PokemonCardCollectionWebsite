<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/setList.css">
        <link rel="stylesheet" href="CSS/index.css">
        <title>Card Sets</title>
    </head>
    <body>
        <?php
            include 'php/header.php';
            include_once 'php/connect.php';
            $sql = "SELECT * FROM `set`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));

            $setListTable = "";

            $setListTable .= '<table>' .
                '<thead>' .
                    '<tr>' .
                        '<th>' . 

                        '</th>' .
                        '<th>' . 

                        '</th>' .
                        '<th colspan="2">' . 
                            'Master' .
                        '</th>' .
                        '<th colspan="2">' . 
                            'Set' .
                        '</th>' .
                    '</tr>' .
                    '<tr>' .
                        '<th>' . 

                        '</th>' .
                        '<th>' .
                            'Name' .
                        '</th>' .
                        '<th>' .
                            'Price' .
                        '</th>' .
                        '<th>' .
                            'Progress' .
                        '</th>' .
                        '<th>' .
                            'Price' .
                        '</th>' .
                        '<th>' .
                            'Progress' .
                        '</th>' . 
                    '</tr>' .
                '</thead>';

            $generationIndex = 0;
            while($row = mysqli_fetch_array($result)){ 
                $setID = $row['set_id'];
                $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                        JOIN `card` ON card.card_id = collection.card_id
                        WHERE `set_id` = $setID AND `variant_id` = 1";
                $setResult = mysqli_query($link, $sql) or die(mysqli_error($link));
                $setOwned = mysqli_fetch_array($setResult);
                
                $sql = "SELECT COUNT(DISTINCT collection.card_id) FROM `collection`
                        JOIN `card` ON card.card_id = collection.card_id
                        WHERE `set_id` = $setID";
                $setResult = mysqli_query($link, $sql) or die(mysqli_error($link));
                $mSetOwned = mysqli_fetch_array($setResult);

                $setListTable .= '<tr id="' . $row['set_name'] . '">' .
                    //Name
                    '<td style="vertical-align:middle">' .
                        '<image src="Images/Symbols/' . $row['set_name'] . '.png" class="setImage"></image>' .
                    '</td>' .
                    '<td style="text-align:left;">' .
                        '<a href="setPage.php?set=' . $row['set_name'] . '">' . $row['set_name'] . '</a>' .
                    '</td>' .
                    //M set price
                    '<td>' .
                        '$' . number_format($row['Mset_price'],2) .
                    '</td>' . 
                    //m set completion
                    '<td>' .
                        $setOwned[0] . '/' .
                        $row['Mset_size'] .
                    '</td>' .
                    //set price
                    '<td>' .
                        '$' . number_format($row['set_price'],2) .
                    '</td>' .
                    //set completion
                    '<td>' . 
                        $mSetOwned[0] . '/' .
                        $row['set_size'] .
                    '</td>' .
                '</tr>';
            }
            $setListTable .= '</table>';

            echo '<div>';
            echo $setListTable;
            echo '</div>';
        ?>
        <p style="text-align:center;">
            This product uses TCGplayer data but is not endorsed or certified by TCGplayer.
        </p>
    </body>
</html>
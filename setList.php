<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/setList.css">
        <title>Card Sets</title>
    </head>
    <body>
        <?php
            include_once 'php/connect.php';
            $sql = "SELECT * FROM `set`";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));

            $setListTable = "";
            $tableOfContents = "";

            $setListTable .= '<table>' .
                '<th>' .
                    'Name' .
                '</th>' .
                '<th>' .
                    'Progress' .
                '</th>';

            $generationIndex = 0;
            $tableOfContents .= '<table>';
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
                
                if($row['generation'] > $generationIndex){
                    $generationIndex++;
                    $tableOfContents .= '<td>Generation ' . $generationIndex . '<br/>';
                }
                
                $tableOfContents .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#' . $row['set_name'] . '">' . $row['set_name'] . '</a><br/>';

                $setListTable .= '<tr id="' . $row['set_name'] . '">' .
                    '<td>' .
                        '<a href="setPage.php?set=' . $row['set_name'] . '">' . $row['set_name'] . '</a>' .
                        '<image src="Images/Symbols/' . $row['set_name'] . '.png" class="setImage"></image>' .
                    '</td>' .
                    '<td class="setProgress">' .
                        'Size: ' . 
                        $setOwned[0] . '/' .
                        $row['set_size'] .
                        '<br/>' .
                        'Msize: ' . 
                        $mSetOwned[0] . '/' .
                        $row['Mset_size'] .
                        '<br/>' .
                        '<label for="master">Master: </label>' .
                        '<progress id="master" value=80 max=100></progress>' .
                        '<text> $' . number_format($row['Mset_price'],2) . '</text>' .
                        '<br/>' .
                        '<br/>' .
                        '<label for="set">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Set: </label>' .
                        '<progress id="set" value=80 max=100></progress>' .
                        '<text> $' . number_format($row['set_price'],2) . '</text>' .
                    '</td>' .
                '</tr>';
            }
            $setListTable .= '</table>';
            $tableOfContents .= '</table>';

            echo $tableOfContents;
            echo $setListTable;
        ?>
        <p>
            This product uses TCGplayer data but is not endorsed or certified by TCGplayer.
        </p>
    </body>
</html>
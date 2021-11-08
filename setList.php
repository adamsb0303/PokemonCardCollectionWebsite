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
                
                $tableOfContents .= '<a href="#' . $row['set_name'] . '">' . $row['set_name'] . '</a><br/>';

                $setListTable .= '<tr id="' . $row['set_name'] . '">' .
                    '<td rowspan=2>' .
                        '<a href="setPage.php?set=' . $row['set_name'] . '">' . $row['set_name'] . '</a>' .
                        '<image src="Images/Symbols/' . $row['set_name'] . '.png" class="setImage"></image>' .
                    '</td>' .
                    '<td style="white-space:nowrap;">' .
                        'Set Size: ' . 
                        $setOwned[0] . '/' .
                        $row['set_size'] .
                        '<br/>' .
                        '$' . number_format($row['set_price'],2) .
                    '</td>' . 
                    '<td style="text-align: right; border-right-style: hidden;">' . 
                        round(($setOwned[0] / $row['set_size']) * 100, 2) . '% ' .
                    '</td>' .
                    '<td style="padding:5px; width:100%;">' .
                        '<progress style="width:100%" value=' . ($setOwned[0] / $row['set_size']) * 100 . ' max=100></progress>' .
                    '</td>' . 
                '</tr>' .
                '<tr>' .
                    '<td style="white-space:nowrap;">' .
                        'Master Set Size: ' . 
                        $mSetOwned[0] . '/' .
                        $row['Mset_size'] .
                        '<br/>' .
                        '$' . number_format($row['Mset_price'],2) .
                    '</td>' .
                    '<td style="text-align: right; border-right-style: hidden;">' . 
                        round(($mSetOwned[0] / $row['Mset_size']) * 100, 2) . '% ' .
                    '</td>' .
                    '<td style="padding:5px; width:100%;">' .
                        '<progress style="width:100%" value=' . ($mSetOwned[0] / $row['Mset_size']) * 100 . ' max=100></progress>' .
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
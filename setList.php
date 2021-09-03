<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/setList.css">
        <title>Card Collection Tracker</title>
    </head>
    <body>
        <text>Card Sets</text> <br />
        <a href="#WoTC Black Star Promos">> Generation 1</a> <br />
        <a href="#Neo Genesis">> Generation 2</a> <br />
        <a href="#Nintendo Black Star Promos">> Generation 3</a> <br />
        <a href="#DP Black Star Promos">> Generation 4</a> <br />
        <a href="#BW Black Star Promos">> Generation 5</a> <br />
        <a href="#XY Black Star Promos">> Generation 6</a> <br />
        <a href="#SM Black Star Promos">> Generation 7</a> <br />
        <a href="#SWSH Black Star Promos">> Generation 8</a>

        <p>
            <?php
                include_once 'php/connect.php';
                $sql = "SELECT * FROM `set`";
                $result = mysqli_query($link, $sql) or die(mysqli_error($link));

                $i = 0;
                while($row = mysqli_fetch_array($result)){
                    $subsetnames = array('Master Set', 'Completion');
                    if($i === 1)
                        $subsetnames = array('1st Edition', 'Unlimited', 'SL 1st Ed.', 'SL Unlim.');
                    else if($i > 0 && $i < 13)
                        $subsetnames = array('1st Edition', 'Unlimited');
                    if($i === 4)
                        $subsetnames = array('Completion');

                    echo '<div id="' . $row['set_name'] . '" class="setnames">' .
                        '<div class="leftSection">' .
                            '<div style="height: 100%;  width: 100%; text-align: center; white-space:nowrap;">' .
                                '<a href="./setPage.php?set=' . $row['set_name'] . '"><text id="' . $row['set_name'] . '" >' . $row['set_name'] . '</text></a> <br />' .
                                '<img src="Images/Logos/' . $row['set_name'] . '.png" alt="' . $row['set_name'] . ' Set Symbol" class="setImage"> <br />' .
                            '</div>' .
                        '</div>' .
                        '<div class="rightSection">';

                    for($j = 0; $j < count($subsetnames); $j++){
                        echo '<div style="display: flex;">' .
                            '<div class="subsectionTitle">' .
                                '<text style="font-size: 10pt;">' . $subsetnames[$j] . '</text>' .
                            '</div>' .
                            '<div class="progressBarPercent">' .
                                '<text style="font-size: 8pt;">80%</text>' .
                            '</div>' .
                            '<div class="progressBarSection">' .
                                '<div class="progressBarBack">' .
                                    '<div class="progressBarTop" ></div>' .
                                '</div>' .
                            '</div>' .
                        '</div>';
                    }
                    echo '</div></div>';
                    $i++;
                }
            ?>
        </p>
        <p>
            This product uses TCGplayer data but is not endorsed or certified by TCGplayer.
        </p>
    </body>
</html>
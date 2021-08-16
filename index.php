<!DOCTYPE html>
<html>
    <head>
        <script src="Card Collection Script.js"></script>
        <link rel="stylesheet" href="Card Collection StyleSheet.css">
        <title>Card Collection Tracker</title>
    </head>
    <body>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td>
                    <h1 style="border: 2px solid black; border-radius: 5px;">Testing Testing Testing</h1>
                </td>
            </tr>
        </table>

        <h1 style="text-align: center">Card Sets</h1>
        <h2 id="Title 1"><a href="#WoTC Black Star Promos">> Generation 1</a></h2>
        
        <h2 id="Title 2"><a href="#Neo Genesis">> Generation 2</a></h2>
        
        <h2 id="Title 3"><a href="#Nintendo Black Star Promos">> Generation 3</a></h2>
        
        <h2 id="Title 4"><a href="#DP Black Star Promos">> Generation 4</a></h2>
        
        <h2 id="Title 5"><a href="#BW Black Star Promos">> Generation 5</a></h2>
        
        <h2 id="Title 6"><a href="#XY Black Star Promos">> Generation 6</a></h2>
        
        <h2 id="Title 7"><a href="#SM Black Star Promos">> Generation 7</a></h2>
        
        <h2 id="Title 8"><a href="#SWSH Black Star Promos">> Generation 8</a></h2>

        <p>
            <?php
                include_once 'connect.php';
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

                    echo '<div id="' . $row['name'] . '" class="setnames">' .
                        '<div class="leftSection">' .
                            '<div style="height: 100%;  width: 100%; text-align: center; white-space:nowrap;">' .
                                '<a href="Card_Sets/Pages/' . $row['name'] . '.html"><text id="' . $row['name'] . '" >' . $row['name'] . '</text></a> <br />' .
                                '<img src="Card_Sets/Logos/' . $row['name'] . '.png" alt="' . $row['name'] . ' Set Symbol" class="setImage"> <br />' .
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
    </body>
</html>
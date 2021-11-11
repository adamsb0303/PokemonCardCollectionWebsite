<?php
    function updateQString($orderByParam, $pageNum){
        $qString = "";
        //Sort Order
        $qString .= "order=" . $orderByParam . "&";
        //Page Num
        $qString .= "page=" . $pageNum;

        return $qString;
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="CSS/index.css">
        <script src="JavaScript/cards.js"></script>
        <title>Cards</title>
    </head>
    <body>
        <?php
            include "PHP/header.php";
            include_once "PHP/connect.php";
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
            $orderByParam = "PRI2";
                if(!empty($_GET['order']))
                    $orderByParam = $_GET['order'];
        ?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <div class="filters">
                <div style="border: 1px solid grey; padding:16px;">
                    <strong>Filters</strong><br/>
                    <input type="text" style="border: 1px solid black" placeholder="search..."><br/>
                    Set<br/>
                    Variant<br/>
                    <input type="submit" name="submit" value="Search"/>
                </div>
            </div>
            <div class="searchResults">
                <table style="table-layout:fixed; width:100%;">
                    <thead>
                        <?php
                            //Basic query
                            $sql = "SELECT * FROM `card` 
                                    JOIN `variant` ON card.variant_id = variant.variant_id
                                    JOIN `set` ON card.set_id = set.set_id ";
                            
                            //select order
                            $sortCategory = substr($orderByParam,0,3);
                            $sortNum = substr($orderByParam, 3);

                            $sortDirection = ($sortNum == 1) ? "ASC" : "DESC";
                            $sortNum = ($sortNum == 1) ? 1 : 2;

                            echo '<th style="width:5%;"></th>';
                            echo '<th style="width:10%;"></th>';
                            switch($sortCategory){
                                case "NAM":
                                    $sql .= "ORDER BY `card_name` " . $sortDirection . " ";
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("NAM" . ($sortNum % 2) + 1, $pageNum) . '">Name</a>
                                                <div class="' . $sortDirection . '"></div>
                                            </div>
                                        </th>';
                                    echo '<th><a href="cards.php?' . updateQString("SET1", $pageNum) . '">Set</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("NUM1", $pageNum) . '">Num</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("VAR1", $pageNum) . '">Variant</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("PRI1", $pageNum) . '">Price</a></th>';
                                    break;
                                case "SET":
                                    $sql .= "ORDER BY `set_name` " . $sortDirection . " ";
                                    echo '<th><a href="cards.php?' . updateQString("NAM1", $pageNum) . '">Name</a></th>';
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("SET" . ($sortNum % 2) + 1, $pageNum) . '">Set</a>
                                                <div class="' . $sortDirection . '"></div>
                                            </div>
                                        </th>';
                                    echo '<th><a href="cards.php?' . updateQString("NUM1", $pageNum) . '">Num</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("VAR1", $pageNum) . '">Variant</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("PRI1", $pageNum) . '">Price</a></th>';
                                    break;
                                case "NUM":
                                    $sql .= "ORDER BY `set_num` " . $sortDirection . " ";
                                    echo '<th><a href="cards.php?' . updateQString("NAM1", $pageNum) . '">Name</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("SET1", $pageNum) . '">Set</a></th>';
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("NUM" . ($sortNum % 2) + 1, $pageNum) . '">Num</a>
                                                <div class="' . $sortDirection . '"></div>
                                            </div>
                                        </th>';
                                    echo '<th><a href="cards.php?' . updateQString("VAR1", $pageNum) . '">Variant</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("PRI1", $pageNum) . '">Price</a></th>';
                                    break;
                                case "VAR":
                                    $sql .= "ORDER BY `variant_name` " . $sortDirection . " ";
                                    echo '<th><a href="cards.php?' . updateQString("NAM1", $pageNum) . '">Name</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("SET1", $pageNum) . '">Set</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("NUM1", $pageNum) . '">Num</a></th>';
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("VAR" . ($sortNum % 2) + 1, $pageNum) . '">Variant</a>
                                                <div class="' . $sortDirection . '"></div>
                                            </div>
                                        </th>';
                                    echo '<th><a href="cards.php?' . updateQString("PRI1", $pageNum) . '">Price</a></th>';
                                    break;
                                case "PRI":
                                    $sql .= "ORDER BY `market_price` " . $sortDirection . " ";
                                    echo '<th><a href="cards.php?' . updateQString("NAM1", $pageNum) . '">Name</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("SET1", $pageNum) . '">Set</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("NUM1", $pageNum) . '">Num</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("VAR1", $pageNum) . '">Variant</a></th>';
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("PRI" . ($sortNum % 2) + 1, $pageNum) . '">Price</a>
                                                <div class="' . $sortDirection . '"></div>
                                            </div>
                                          </th>';
                                    break;
                                default:
                                    $sql .= "ORDER BY `market_price` DESC ";
                                    echo '<th><a href="cards.php?' . updateQString("NAM1", $pageNum) . '">Name</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("SET1", $pageNum) . '">Set</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("NUM1", $pageNum) . '">Num</a></th>';
                                    echo '<th><a href="cards.php?' . updateQString("VAR1", $pageNum) . '">Variant</a></th>';
                                    echo '<th>
                                            <div class="filteredCategory">
                                                <a href="cards.php?' . updateQString("PRI1", $pageNum) . '">Price</a>
                                                <div class="DESC"></div>
                                            </div>
                                          </th>';
                                    break;
                            }
                        ?>
                    </thead>
                    <tbody>
                        <?php
                            //select page
                            $sql .= "LIMIT " . ($pageNum - 1) * 50 . ", " . 50;

                            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                            while($card = mysqli_fetch_array($result)){
                                echo '<tr>';
                                    echo '<td><input type="checkbox"></td>';
                                    echo '<td><image src="https://product-images.tcgplayer.com/' . $card['product_id'] . '.jpg" style="height: 36pt; width: auto;"></image></td>';
                                    echo '<td style="text-align:left; width: 1em">' . $card['card_name'] . '</td>';
                                    echo '<td>' . $card['set_name'] . '</td>';
                                    echo '<td>' . $card['set_num'] . '</td>';
                                    echo '<td>' . $card['variant_name'] . '</td>';
                                    echo '<td>$' . $card['market_price'] . '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
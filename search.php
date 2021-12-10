<?php
    function updateQString($search, $sets, $orderByParam, $pageNum){
        $qString = "";
        //Search
        if($search != "")
            $qString .= "q=" . $search . "&";
        //sets
        if(implode($sets) != "")
            $qString .= "set=" . implode(",", $sets) . "&";
        //Sort Order
        $qString .= "sort=" . $orderByParam . "&";
        //Page Num
        $qString .= "page=" . $pageNum;

        return $qString;
    }

    function addToArray($arr, $val){
        array_push($arr, $val);
        return $arr;
    }

    function removeFromArray($arr, $val){
        for($i = 0; $i < count($arr); $i++)
            if($arr[$i] == $val){
                unset($arr[$i]);
                break;
            }
        
        return array_values($arr);
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="CSS/index.css">
        <title>Search</title>
    </head>
    <body>
        <?php
            include "PHP/header.php";
            include_once "PHP/connect.php";
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
            $orderByParam = "PRI2";
                if(!empty($_GET['sort']))
                    $orderByParam = $_GET['sort'];
            $search = "";
                if(!empty($_GET['q']))
                    $search = $_GET['q'];
            $set = [];
                if(!empty($_GET['set']))
                    $set = explode(",", $_GET['set']);
        ?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <div class="filters">
                <div style="border: 1px solid grey; padding:16px;">
                    <h3 style="text-decoration-line: underline;">Filters</h3>
                    <ul>
                        <?php
                            $sql = "SELECT `set_name`, `generation` FROM `set`
                                    ORDER BY `generation` ASC, `set_id`";
                            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                            $currentGen = 1;
                            echo '<strong>Search</strong><br/>';
                            echo '<form method="get">';
                                echo '<input type="text" name="q" class="searchField" style="" placeholder="search...">';
                                echo '<input type="submit" value="Submit" class="searchSubmit"><br/><br/>';
                            echo '</form>';
                            echo '<strong>Set</strong><br/>';
                            echo 'Generation 1<br/>';
                            while($setName = mysqli_fetch_array($result)){
                                if($currentGen < $setName[1]){
                                    echo 'Generation ' . $setName[1] . '<br/>';
                                    $currentGen = $setName[1];
                                }
                                echo '<div class="setFilter">';
                                if(in_array($setName[0], $set))
                                    echo '<input type="checkbox" id="' . $setName[0] . '" checked> <a href="search.php?' . updateQString($search, removeFromArray($set, $setName[0]), $orderByParam, $pageNum) . '">' . $setName[0] . '</a></input><br/>';
                                else
                                    echo '<input type="checkbox" id="' . $setName[0] . '"> <a href="search.php?' . updateQString($search, addToArray($set, $setName[0]), $orderByParam, $pageNum) . '">' . $setName[0] . '</a></input><br/>';
                                echo '</div>';
                            }
                        ?>
                    </ul>
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

                            $countSQL = "SELECT COUNT(`card_id`) FROM `card` 
                                        JOIN `set` ON card.set_id = set.set_id ";
                            if($search != "" || !empty($set)){
                                $sql .= "WHERE ";
                                $countSQL .= "WHERE ";
                            }

                            //word search
                            if($search != ""){
                                $sql .= "`card_name` LIKE '%" . $search . "%'";
                                $countSQL .= "`card_name` LIKE '%" . $search . "%'";
                                if(!empty($set)){
                                    $sql .= " AND (";
                                    $countSQL .= " AND (";
                                }
                            }

                            //restrict set
                            for($i = 0; $i < count($set); $i++){
                                if($i == 0){
                                    $sql .= "`set_name` = '" . $set[$i] . "' ";
                                    $countSQL .= "`set_name` = '" . $set[$i] . "' ";
                                }

                                $sql .= "OR `set_name` = '" . $set[$i] . "' ";
                                $countSQL .= "OR `set_name` = '" . $set[$i] . "' ";

                                if($i == count($set) - 1 && $search != ""){
                                    $sql .= ") ";
                                    $countSQL .= ") ";
                                }
                            }
                            
                            //select order
                            $sortCategory = substr($orderByParam,0,3);
                            $sortNum = substr($orderByParam, 3);

                            $sortDirection = ($sortNum == 1) ? "ASC" : "DESC";
                            $sortCategory = ($sortNum == 1 || $sortNum == 2) ? $sortCategory : "";

                            echo '<th style="width:5%;"></th>';
                            echo '<th style="width:10%;"></th>';
                            //Sort Name
                            if($sortCategory == "NAM"){
                                $sql .= "ORDER BY `card_name` " . $sortDirection . ", `card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="search.php?' . updateQString($search, $set, "NAM" . ($sortNum % 3) + 1, $pageNum) . '">Name</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="search.php?' . updateQString($search, $set, "NAM1", $pageNum) . '">Name</a></th>';

                            //Sort Set
                            if($sortCategory == "SET"){
                                $sql .= "ORDER BY `set_name` " . $sortDirection . ", `card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="search.php?' . updateQString($search, $set, "SET" . ($sortNum % 3) + 1, $pageNum) . '">Set</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="search.php?' . updateQString($search, $set, "SET1", $pageNum) . '">Set</a></th>';

                            //Sort Set Number
                            if($sortCategory == "NUM"){
                                $sql .= "ORDER BY cast(`set_num` as unsigned) " . $sortDirection . ", `card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="search.php?' . updateQString($search, $set, "NUM" . ($sortNum % 3) + 1, $pageNum) . '">Num</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="search.php?' . updateQString($search, $set, "NUM1", $pageNum) . '">Num</a></th>';

                            //Sort Variant
                            if($sortCategory == "VAR"){
                                $sql .= "ORDER BY `variant_name` " . $sortDirection . ", `card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="search.php?' . updateQString($search, $set, "VAR" . ($sortNum % 3) + 1, $pageNum) . '">Variant</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                    </th>';
                            }else
                                echo '<th><a href="search.php?' . updateQString($search, $set, "VAR1", $pageNum) . '">Variant</a></th>';

                            //Sort Price
                            if($sortCategory == "PRI"){
                                $sql .= "ORDER BY `market_price` " . $sortDirection . ", `card_id` ";
                                echo '<th>
                                        <div class="filteredCategory">
                                            <a href="search.php?' . updateQString($search, $set, "PRI" . ($sortNum % 3) + 1, $pageNum) . '">Price</a>
                                            <div class="' . $sortDirection . '"></div>
                                        </div>
                                      </th>';
                            }else
                                echo '<th><a href="search.php?' . updateQString($search, $set, "PRI1", $pageNum) . '">Price</a></th>';
                        ?>
                    </thead>
                    <tbody>
                        <?php
                            $resultQuery = mysqli_query($link, $countSQL) or die(mysqli_error($link));
                            $resultSize = mysqli_fetch_array($resultQuery);
                            $max = ceil($resultSize[0] / 50);

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
                <div class="pageSelect">
                    <?php
                        if($pageNum == 1)
                            echo '<a style="color:grey">ᐸ</a>';
                        else
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">ᐸ</a>';

                        if($max <= 5){
                            for($i = 1; $i <= $max; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                        }else if($pageNum <= 4){
                            for($i = 1; $i <= 5; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                            echo '<a>...</a>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
                        }else if($pageNum > $max - 4){
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
                            echo '<a>...</a>';
                            for($i = $max - 4; $i <= $max; $i++){
                                if($i == $pageNum)
                                    echo '<strong>'. $i . '</strong>';
                                else
                                    echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
                            }
                        }else{
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
                            if($pageNum - 2 != 1)
                                echo '<a>...</a>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum - 2) . '">' . $pageNum - 2 . '</a>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">' . $pageNum - 1 . '</a>';
                            echo '<strong>' . $pageNum . '</strong>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">' . $pageNum + 1 . '</a>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum + 2) . '">' . $pageNum + 2 . '</a>';
                            if($pageNum + 2 != $max)
                                echo '<a>...</a>';
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
                        }
                        if($pageNum == $max)
                            echo '<a style="color:grey">ᐳ</a>';
                        else
                            echo '<a href="search.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">ᐳ</a>';
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
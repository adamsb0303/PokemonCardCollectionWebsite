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
                        echo '<input type="checkbox" id="' . $setName[0] . '" checked> <a href="' . $pageName . '.php?' . updateQString($search, removeFromArray($set, $setName[0]), $orderByParam, $pageNum) . '">' . $setName[0] . '</a></input><br/>';
                    else
                        echo '<input type="checkbox" id="' . $setName[0] . '"> <a href="' . $pageName . '.php?' . updateQString($search, addToArray($set, $setName[0]), $orderByParam, $pageNum) . '">' . $setName[0] . '</a></input><br/>';
                    echo '</div>';
                }
            ?>
        </ul>
    </div>
</div>
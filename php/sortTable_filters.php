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
                    if(in_array($setName[0], $set)){
                        $newLink =  $pageName . '.php?' . updateQString($search, removeFromArray($set, $setName[0]), $orderByParam, $pageNum);
                        echo '<input type="checkbox" value="' . $newLink . '" onClick="if (!this.checked) { window.location = this.value; }" checked> <a href="' . $newLink . '">' . $setName[0] . '</a></input><br/>';
                    }else{
                        $newLink =  $pageName . '.php?' . updateQString($search, addToArray($set, $setName[0]), $orderByParam, $pageNum);
                        echo '<input type="checkbox" value="' . $newLink . '" onClick="if (this.checked) { window.location = this.value; }"> <a href="' . $newLink . '">' . $setName[0] . '</a></input><br/>';
                    }
                    echo '</div>';
                }
            ?>
        </ul>
    </div>
</div>
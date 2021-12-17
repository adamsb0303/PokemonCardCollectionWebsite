<div class="pageSelect">
    <?php
        if($pageNum == 1)
            echo '<a style="color:grey">ᐸ</a>';
        else
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">ᐸ</a>';

        if($max <= 5){
            for($i = 1; $i <= $max; $i++){
                if($i == $pageNum)
                    echo '<strong>'. $i . '</strong>';
                else
                    echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
            }
        }else if($pageNum <= 4){
            for($i = 1; $i <= 5; $i++){
                if($i == $pageNum)
                    echo '<strong>'. $i . '</strong>';
                else
                    echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
            }
            echo '<a>...</a>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
        }else if($pageNum > $max - 4){
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
            echo '<a>...</a>';
            for($i = $max - 4; $i <= $max; $i++){
                if($i == $pageNum)
                    echo '<strong>'. $i . '</strong>';
                else
                    echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $i) . '">' . $i .'</a>';
            }
        }else{
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, 1) . '">1</a>';
            if($pageNum - 2 != 1)
                echo '<a>...</a>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum - 2) . '">' . ($pageNum - 2) . '</a>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum - 1) . '">' . ($pageNum - 1) . '</a>';
            echo '<strong>' . $pageNum . '</strong>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">' . ($pageNum + 1) . '</a>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum + 2) . '">' . ($pageNum + 2) . '</a>';
            if($pageNum + 2 != $max)
                echo '<a>...</a>';
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $max) . '">' . $max . '</a>';
        }
        if($pageNum == $max)
            echo '<a style="color:grey">ᐳ</a>';
        else
            echo '<a href="' . $pageName . '.php?' . updateQString($search, $set, $orderByParam, $pageNum + 1) . '">ᐳ</a>';
    ?>
</div>
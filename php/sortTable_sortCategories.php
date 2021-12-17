<?php
$sortValues;
if($pageName == 'search')
    $sortValues = array("Name", "Variant", "Set", "Num", "Price");
if($pageName == 'inventory')
    $sortValues = array("Name", "Variant", "Set", "Num", "Current Market Price", "Purchase Date", "Condition", "Gain");

if($search != "" || !empty($set)){
    $sql .= "AND ";
    $countSQL .= "AND ";
}

//word search
if($search != ""){
    $sql .= "CONCAT(`card_name`,`variant_name`) LIKE '%" . $search . "%'";
    $countSQL .= "CONCAT(`card_name`,`variant_name`) LIKE '%" . $search . "%'";

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
    }else{
        $sql .= "OR `set_name` = '" . $set[$i] . "' ";
        $countSQL .= "OR `set_name` = '" . $set[$i] . "' ";
    }

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

//card image
if($pageName == 'search')
    echo '<th style="width:5%;"></th>';
echo '<th style="width:10%;"></th>';

//Sort Name
if($sortCategory == "NAM"){
    $sql .= "ORDER BY `card_name` " . $sortDirection . ", `card`.`card_id` ";
    echo '<th>
            <div class="filteredCategory">
                <a href="' . $pageName . '.php?' . updateQString($search, $set, "NAM" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[0] . '</a>
                <div class="' . $sortDirection . '"></div>
            </div>
        </th>';
}else
    echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "NAM1", $pageNum) . '">'. $sortValues[0] . '</a></th>';

//Sort Variant
if($sortCategory == "VAR"){
    $sql .= "ORDER BY `variant_name` " . $sortDirection . ", `card`.`card_id` ";
    echo '<th>
            <div class="filteredCategory">
                <a href="' . $pageName . '.php?' . updateQString($search, $set, "VAR" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[1] . '</a>
                <div class="' . $sortDirection . '"></div>
            </div>
        </th>';
}else
    echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "VAR1", $pageNum) . '">'. $sortValues[1] . '</a></th>';

//Sort Set
if($sortCategory == "SET"){
    $sql .= "ORDER BY `set_name` " . $sortDirection . ", `card`.`card_id` ";
    echo '<th>
            <div class="filteredCategory">
                <a href="' . $pageName . '.php?' . updateQString($search, $set, "SET" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[2] . '</a>
                <div class="' . $sortDirection . '"></div>
            </div>
        </th>';
}else
    echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "SET1", $pageNum) . '">'. $sortValues[2] . '</a></th>';

//Sort Set Number
if($sortCategory == "NUM"){
    $sql .= "ORDER BY cast(`set_num` as unsigned) " . $sortDirection . ", `card`.`card_id` ";
    echo '<th>
            <div class="filteredCategory">
                <a href="' . $pageName . '.php?' . updateQString($search, $set, "NUM" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[3] . '</a>
                <div class="' . $sortDirection . '"></div>
            </div>
        </th>';
}else
    echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "NUM1", $pageNum) . '">'. $sortValues[3] . '</a></th>';

//Sort Price
if($sortCategory == "PRI"){
    $sql .= "ORDER BY `market_price` " . $sortDirection . ", `card`.`card_id` ";
    echo '<th>
            <div class="filteredCategory">
                <a href="' . $pageName . '.php?' . updateQString($search, $set, "PRI" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[4] . '</a>
                <div class="' . $sortDirection . '"></div>
            </div>
            </th>';
}else
    echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "PRI1", $pageNum) . '">'. $sortValues[4] . '</a></th>';

if($pageName == 'inventory'){
    //Sort Purchase Date
    if($sortCategory == "DAT"){
        $sql .= "ORDER BY `purchase_date` " . $sortDirection . ", `card`.`card_id` ";
        echo '<th>
                <div class="filteredCategory">
                    <a href="' . $pageName . '.php?' . updateQString($search, $set, "DAT" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[5] . '</a>
                    <div class="' . $sortDirection . '"></div>
                </div>
                </th>';
    }else
        echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "DAT1", $pageNum) . '">'. $sortValues[5] . '</a></th>';

    //Sort Condition
    if($sortCategory == "CON"){
        $sql .= "ORDER BY `condition` " . $sortDirection . ", `card`.`card_id` ";
        echo '<th>
                <div class="filteredCategory">
                    <a href="' . $pageName . '.php?' . updateQString($search, $set, "CON" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[6] . '</a>
                    <div class="' . $sortDirection . '"></div>
                </div>
                </th>';
    }else
        echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "CON1", $pageNum) . '">'. $sortValues[6] . '</a></th>';

    //Sort Current Gain
    if($sortCategory == "PRF"){
        $sql .= "ORDER BY (`market_price` - `purchase_price`) " . $sortDirection . ", `card`.`card_id` ";
        echo '<th>
                <div class="filteredCategory">
                    <a href="' . $pageName . '.php?' . updateQString($search, $set, "PRF" . ($sortNum % 3 + 1), $pageNum) . '">'. $sortValues[7] . '</a>
                    <div class="' . $sortDirection . '"></div>
                </div>
                </th>';
    }else
        echo '<th><a href="' . $pageName . '.php?' . updateQString($search, $set, "PRF1", $pageNum) . '">'. $sortValues[7] . '</a></th>';
}
?>
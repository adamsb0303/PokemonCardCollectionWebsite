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
<?php
    //300 requests per minute max
    //expires 9/5
    include_once "bearer_token.php";
    set_time_limit(60000);

    $sql = "SELECT `set_id` FROM `set`";
    $sets = mysqli_query($link, $sql) or die(mysqli_error($link));

    $index = 1;
    while($setRow = mysqli_fetch_array($sets)){
        $setNum = $setRow['set_id'];
        $sql = "SELECT * FROM `card` WHERE `set_id` = $setNum
                GROUP BY `product_id`";
        $products = mysqli_query($link, $sql) or die(mysqli_error($link));

        while($productRow = mysqli_fetch_array($products)){
            if($productRow['product_id'] != 0){
                updateCardPrice($productRow['product_id'], $bearerToken);
            }
            if($index % 300 == 0){
                sleep(60);
            }  
            $index++;
        }

        $sql = "UPDATE `set` SET `set_price` = (SELECT SUM(`market_price`) FROM `card` WHERE `set_id` = $setNum AND `variant_id` = 1),
                                `Mset_price` = (SELECT SUM(`market_price`) FROM `card` WHERE `set_id` = $setNum)
                WHERE `set_id` = $setNum";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }

    function updateCardPrice($product_id, $bearerToken){
        include "connect.php";
        $productPrices = getProductDetails($product_id, $bearerToken);
    
        $sql = "SELECT * FROM `card` WHERE `card`.`product_id` = $product_id";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
    
        $firstCardRow = mysqli_fetch_array($result);
        $cardIDIndex = $firstCardRow['card_id'];

        for($i = 0; $i < count($productPrices); $i++){
            if($productPrices[$i]->marketPrice != null){
                $marketprice = $productPrices[$i]->marketPrice;
                $marketaverage = $productPrices[$i]->midPrice;

                $marketprice = ($marketprice == null) ? 0 : $marketprice;
                $marketaverage = ($marketaverage == null) ? 0 : $marketaverage;

                $sql = "UPDATE `card` SET `market_price` = $marketprice, `average_price` = $marketaverage WHERE `card_id` = $cardIDIndex";
                mysqli_query($link, $sql) or die(mysqli_error($link));

                $cardIDIndex++;
            }
        }
    }

    function getProductDetails($id, $bearerToken){
        $curl = curl_init();

        curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.tcgplayer.com/v1.37.0/pricing/product/$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: bearer $bearerToken"
        ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $results = json_decode($response)->results;
            return $results;
        }
    }
?>

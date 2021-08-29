<?php
    //300 requests per minute max
    //expires 9/5
    include "bearer_token.php";
    $product_id = 106999;

    include_once "connect.php";
    $productPrices = getProductDetails($product_id, $bearerToken);

    $sql = "SELECT * FROM `card` WHERE `card`.`product_id` = $product_id";
    $result = mysqli_query($link, $sql) or die(mysqli_error($link));

    $firstCardRow = mysqli_fetch_array($result);
    $firstCardIDIndex = $firstCardRow['card_id'];

    $cardIDIndex = 0;
    for($i = 0; $i < count($productPrices); $i++){
        if($productPrices[$i]->marketPrice != null){
            $marketprice = $productPrices[$i]->marketPrice;
            $marketaverage = $productPrices[$i]->midPrice;

            $sql = "UPDATE `card` SET `market_price` = $marketprice WHERE `card`.`card_id` = $firstCardIDIndex + $cardIDIndex";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            
            $sql = "UPDATE `card` SET `average_price` = $marketaverage WHERE `card`.`card_id` = $firstCardIDIndex + $cardIDIndex";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
            $cardIDIndex++;
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

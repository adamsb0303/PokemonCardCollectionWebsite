<?php
    $cardID = 1;
    if(!empty($_GET['id']))
        $cardID = $_GET['id'];

    include_once "PHP/connect.php";
    $sql = "SELECT * FROM `card`
            JOIN `variant` ON card.variant_id = variant.variant_id 
            JOIN `set` ON card.set_id = set.set_id 
            WHERE card.`set_num` = (SELECT set_num FROM `card` WHERE `card_id` = $cardID)
            AND card.`set_id` = (SELECT set_id FROM `card` WHERE `card_id` = $cardID)";
    $results = mysqli_query($link, $sql) or die(mysqli_error($link));

    $cards = array();
    $cards = array_pad($cards, $results->num_rows, array());

    for($i = 0; $i < $results->num_rows; $i++){
        $data = mysqli_fetch_array($results);
        $cards[$data['card_id']] = $cards[$i];
        unset($cards[$i]);
        $cards[$data['card_id']] = $data;
    }

    $setName = $cards[$cardID]['set_name'];
    $cardName = $cards[$cardID]['card_name'];
    $setNum = $cards[$cardID]['set_num'];
    $productID = $cards[$cardID]['product_id'];
    $variant = $cards[$cardID]['variant_name'];
    $marketPrice = $cards[$cardID]['market_price'];
?>

<html>
    <head>
        <link rel="stylesheet" href="CSS/index.css">
        <title><?php echo $cardName . " (" . $setNum . ") - " . $variant; ?></title>
    </head>
    <body>
        <?php include "PHP/header.php";?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <div style="width:100%;">
                <div style="display:flex; width:100%;">
                    <image src="https://product-images.tcgplayer.com/<?php echo $productID ?>.jpg" style="height: auto; width: 30%;"></image>
                    <div class="cardInfo">
                        <?php
                            echo $cardName . ' - ' . $setNum . '<br/>';
                            echo $variant . '<br/>';
                            echo $setName .'<br/><br/>';
                            echo 'Current Market Price: <br/>';
                            echo '<text style="color: #008700; font-size:30pt;">$' . $marketPrice . '</text>';
                        ?>
                    </div>
                </div>
                <div style="display: flex; width:100%;">
                    <div style="width: 50%;">
                        
                    </div>
                    <div>
                    <br><u>Variants...</u><br>
                        <?php
                            foreach ($cards as $var){
                                if($var['card_id'] == $cardID)
                                    echo '<strong>' . $var['variant_name'] . '</strong>';
                                else
                                    echo '<a href="card.php?id=' . $var['card_id'] . '">' . $var['variant_name'] . '</a>';
                                echo ' - $' . $var['market_price'] . '<br>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
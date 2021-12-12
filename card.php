<?php
    //pulls id of card
    $cardID = 1;
    if(!empty($_GET['id']))
        $cardID = $_GET['id'];

    include_once "php/connect.php";

    //push form data to sql server
    if(isset($_POST['submit'])){
        $formValues = array(1, $cardID);
        $condition =  NULL;
        $purchasePrice = NULL;
        $purchaseDate = NULL;

        $queryValues = "`user_id`, `card_id`";

        if($_POST['condition'] != ''){
            $queryValues .= ', `condition`';
            array_push($formValues, '\'' . $_POST['condition'] . '\'');
        }
        if($_POST['price'] != ''){
            $queryValues .= ', `purchase_price`';
            array_push($formValues, $_POST['price']);
        }
        if($_POST['date'] != ''){
            $queryValues .= ', `purchase_date`';
            array_push($formValues, '\'' . $_POST['date'] . '\'');
        }

        $sql = "INSERT INTO `collection` ($queryValues) VALUES (" . implode(", ", $formValues) . ");";
        mysqli_query($link, $sql) or die(mysqli_error($link));
    }

    //get every variant of the given card
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

    //information of the given card
    $setName = $cards[$cardID]['set_name'];
    $cardName = $cards[$cardID]['card_name'];
    $setNum = $cards[$cardID]['set_num'];
    $productID = $cards[$cardID]['product_id'];
    $variant = $cards[$cardID]['variant_name'];
    $marketPrice = $cards[$cardID]['market_price'];
?>

<html>
    <head>
        <?php include_once "php/head.php" ?>
        <title><?php echo $cardName . " (" . $setNum . ") - " . $variant; ?></title>
    </head>
    <body>
        <?php include "php/header.php";?>
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
                        <!--Inventory-->
                        <form method="post">
                            <br><text>Inventory: </text><br/>
                            <!--Price Purchased-->
                            <text>Price Purchased: </text>
                            <input type="number" min="0" step="0.01" placeholder = "$0.00" name="price"/>
                            <button title="Calculates price based off potential pulls">From Pack</button>
                            <br/>
                            <!--Date Purchased-->
                            <text>Date Purchased: </text>
                            <input type="date" name="date"></input>
                            <br/>
                            <!--Condition-->
                            <text>Condition: </text>
                            <select name="condition">
                                    <option></option>
                                    <option value="NM">NM</option>
                                    <option value="LP">LP</option>
                                    <option value="MP">MP</option>
                                    <option value="HP">HP</option>
                                    <option value="DMG">DMG</option>
                                </select>
                            <br/>
                            <!--Submit-->
                            <input type="submit" name="submit" value="Submit" disabled/>
                            <br/><br/>
                        </form>
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
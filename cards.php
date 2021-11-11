<html>
    <head>
        <link rel="stylesheet" href="CSS/index.css">
        <script src="JavaScript/cards.js"></script>
        <title>Inventory</title>
    </head>
    <body>
        <?php
            include "PHP/header.php";
            include_once "PHP/connect.php";
            $pageNum = 1;
                if(!empty($_GET['page']))
                    $pageNum = $_GET['page'];
        ?>
        <div class="root" style="padding-top:16px; padding-bottom:16px;">
            <div class="filters">
                Filters<br/>
                <input type="text" style="border: 1px solid black" placeholder="search..."><br/>
                Set<br/>
                Variant<br/>
                <input type="submit" name="submit" value="Search"/>
            </div>
            <div class="searchResults">
                <table>
                    <thead>
                        <th></th>
                        <th></th>
                        <th>Name</th>
                        <th>Set</th>
                        <th>Num</th>
                        <th>Variant</th>
                        <th>Price</th>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM `card` 
                                    JOIN `variant` ON card.variant_id = variant.variant_id
                                    JOIN `set` ON card.set_id = set.set_id
                                    ORDER BY `market_price` DESC
                                    LIMIT " . ($pageNum - 1) * 50 . ", " . $pageNum * 50;
        
                            $result = mysqli_query($link, $sql) or die(mysqli_error($link));
                            while($card = mysqli_fetch_array($result)){
                                echo '<tr>';
                                    echo '<td><input type="checkbox"></td>';
                                    echo '<td><image src="https://product-images.tcgplayer.com/35x48/' . $card['product_id'] . '.jpg" style="height: 36pt; width: auto;"></image></td>';
                                    echo '<td style="text-align:left">' . $card['card_name'] . '</td>';
                                    echo '<td>' . $card['set_name'] . '</td>';
                                    echo '<td>' . $card['set_num'] . '</td>';
                                    echo '<td>' . $card['variant_name'] . '</td>';
                                    echo '<td>$' . $card['market_price'] . '</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
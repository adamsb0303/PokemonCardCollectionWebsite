<!DOCTYPE html>
<html>
    <head>
        <script src="Card Collection Script.js"></script>
        <link rel="stylesheet" href="Card Collection StyleSheet.css">
        <title>Base Set</title>
    </head>
    <body>
        <table style="margin-left: auto; margin-right: auto;">
            <tr>
                <td>
                    <h1 style="border: 2px solid black; border-radius: 5px;">Testing Testing Testing</h1>
                </td>
            </tr>
        </table>

        <h1 style="text-align: center;">Card Sets</h1>
        <div style="display: flex; width: 100%; justify-content: space-around;">
            <h2 id="Title 1" onclick="collapse(1)"><a href="#Title 1">1st Edition</a></h2>
            <div id="Generation1"></div>
            
            <h2 id="Title 2" onclick="collapse(2)"><a href="#Title 2">Unlimited</a></h2>
            <div id="Generation2"></div>
            
            <h2 id="Title 3" onclick="collapse(3)"><a href="#Title 3">Shadowless 1st Edition</a></h2>
            <div id="Generation3"></div>
            
            <h2 id="Title 4" onclick="collapse(4)"><a href="#Title 4">Shadowless Unlimited</a></h2>
            <div id="Generation4"></div>
        </div>
        <?php
            include_once 'connect.php';
            $sql = "SELECT * FROM `card` WHERE `set_id` = 2 AND `version` = ";
            $result = mysqli_query($link, $sql) or die(mysqli_error($link));

            while($row = mysqli_fetch_array($result)){
                echo '<p>' . $row['set_num'] . ': ' . 
            }
        ?>
    </body>
</html>
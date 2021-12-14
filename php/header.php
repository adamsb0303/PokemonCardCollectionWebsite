<?php $signedIn = isset($_COOKIE['ID']) && isset($_COOKIE['Key']);?>

<div class="navMenu">
    <div class="root">
        <div class="siteLinks">
            <nav>
                <a class="navItem" href="./index.php">Home</a>
                <a class="navItem" href="./setList.php">Card Sets</a>
                <?php if($signedIn)
                    echo '<a class="navItem" href="./inventory.php">Inventory</a>';
                ?>
                <a class="navItem" href="./search.php">Search</a>
            </nav>
        </div>
        <div class="siteLinks">
            <nav>
                <?php include "php/login.php" ?>
            </nav>
        </div>
    </div>
</div>
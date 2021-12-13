<div class="navMenu">
    <div class="root">
        <div class="siteLinks">
            <nav>
                <a class="navItem" href="./index.php">Home</a>
                <a class="navItem" href="./setList.php">Card Sets</a>
                <a class="navItem" href="./inventory.php">Inventory</a>
                <a class="navItem" href="./search.php">Search</a>
            </nav>
        </div>
        <div class="siteLinks">
            <nav>
            <?php
                include "php/google_connect.php";

                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                
                $cookie_name = "username";

                if(isset($_GET['action'])){
                    if($_GET['action'] == 'logout'){
                        setcookie($cookie_name, "", time() - 3600);
                        header("Location:.");
                    }
                }
                if (isset($_COOKIE['username'])) {
                    echo '<a class="navItem" href="?action=logout">Logout</a>';
                } else
                // authenticate code from Google OAuth Flow
                if (isset($_GET['code'])) {
                    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $client->setAccessToken($token['access_token']);
                    
                    // get profile info
                    $google_oauth = new Google_Service_Oauth2($client);
                    $google_account_info = $google_oauth->userinfo->get();
                    $email =  $google_account_info->email;
                    $name =  $google_account_info->name;

                    $cookie_value = $name;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                    header("Location: .");
                // now you can use this profile info to create account in your website and make user logged in.
                } else {
                    echo '<a class="navItem" href="'.$client->createAuthUrl().'">Login</a>';
                }
            ?>
            </nav>
        </div>
    </div>
</div>
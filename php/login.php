<?php
    include "php/google_connect.php";
    include "php/connect.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    $cookie_name = "username";

    //Logout
    if(isset($_GET['action'])){
        if($_GET['action'] == 'logout'){
            setcookie("username", "", time() - 3600);
            header("Location:.");
        }
    }
    
    //If the user is already logged in, display logout
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

        $sql = "SELECT user_email FROM user
                WHERE `user_email` = '$email'";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));

        if(!empty($result)){
            $sql = "INSERT INTO user(user_name, user_email)
                    VALUES ('$name', '$email')";
            $addUser = mysqli_query($link, $sql) or die(mysqli_error($link));
        }

        //create email cookie
        $cookie_value = $email;
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        header("Location: .");
    // now you can use this profile info to create account in your website and make user logged in.
    } else {
        echo '<a class="navItem" href="'.$client->createAuthUrl().'">Login</a>';
    }
?>
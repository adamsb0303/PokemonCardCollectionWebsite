<?php
    include "php/google_connect.php";
    include "php/connect.php";

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Logout
    if(isset($_GET['action'])){
        if($_GET['action'] == 'logout'){
            setcookie("ID", "", time() - 3600);
            header("Location:.");
        }
    }
    
    //If the user is already logged in, display logout
    if (isset($_COOKIE['ID'])) {
        $sql = "SELECT * FROM user
                WHERE user_id = " . $_COOKIE['ID'];
        $userIdResult = mysqli_query($link, $sql) or die(mysqli_error($link));
        $userId = mysqli_fetch_array($userIdResult);
        echo '<a class="navItem" href="?action=logout">' . $userId['user_name'] . '</a>';
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

        $sql = "SELECT `user_email` FROM user
                WHERE `user_email` = '$email'";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));

        if($result->num_rows == 0){
            $sql = "INSERT INTO user(user_name, user_email)
                    VALUES ('$name', '$email')";
            mysqli_query($link, $sql) or die(mysqli_error($link));
        }

        $sql = "SELECT user_id FROM user
                WHERE user_name = '$name' AND user_email = '$email'";
        $userIdResult = mysqli_query($link, $sql) or die(mysqli_error($link));
        $userId = mysqli_fetch_array($userIdResult)['user_id'];

        //create email cookie
        $cookie_name = "ID";
        $cookie_value = $userId;
        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
        header("Location: .");
    // now you can use this profile info to create account in your website and make user logged in.
    } else {
        echo '<a class="navItem" href="'.$client->createAuthUrl().'">Login</a>';
    }
?>
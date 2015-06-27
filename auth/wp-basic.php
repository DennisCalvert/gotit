<?php
    
    //Force SSL so that passwords aren't sent in the clear.
    if($_SERVER["HTTPS"] != "on") {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
    
    if($_COOKIE["session"] != "isValid"){ 
        //Here's where the Wordpress magic happens.
        define('WP_USE_THEMES', false);
        require(__dir__."/../../wp-load.php");
    
        //Check to see if the user has an active auth session
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
           //Show the auth dialog box
           http_basic_auth();
        } else {
    
           //Get the input data.
           $user = stripslashes($_SERVER['PHP_AUTH_USER']);
           $pass = stripslashes($_SERVER['PHP_AUTH_PW']);
    
           //Check WordPress for correct credentials.
           $auth = wp_user_pass_ok($user, $pass);
    
           if($auth !== true) {
              //Whoops! Try again!
              http_basic_auth();
              die();
           }
           //Otherwiuse, let em through!
           // TODO: Add cookie for longer than 1 session authentication.
           setcookie("session","isValid",time() + (10 * 365 * 24 * 60 * 60));
        }
    }
    function http_basic_auth() {
        $realm = 'Restricted-use your Wordpress credentials.';
        header('WWW-Authenticate: Basic realm="' . $realm . '"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Whoops! You hit the cancel button. Use your WordPress credentials to gain access. Hit reload to try again.';
        exit;
    }
    
    function wp_user_pass_ok($user_login, $user_pass) {
        $user = wp_authenticate($user_login, $user_pass);
    
        //Make sure the user authenticates and can edit posts
        //(contributor level or higher - we don't want to give subscribers access)
        if ( is_wp_error($user) ||  !user_can($user, 'edit_posts'))
            return false;
    
        return true;
    }

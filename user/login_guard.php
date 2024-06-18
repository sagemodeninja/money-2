<?php
    function CheckLogin() {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/helper.php";

        $isSignedIn = @$_SESSION["user_is_signed-in"];
        $clientId =  getenv('GOOGLE_CLIENT_ID');
        $redirectURI = getFullHost() . "/user/verify_google.php";
        
        if(!isset($isSignedIn)) {
            header("location: https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=$clientId&redirect_uri=$redirectURI&scope=openid+email+profile");
        }
    }
?>
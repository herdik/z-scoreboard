<?php

class Auth {
    /**
     * @return boolean true if user is logged in or false in case is not logged in
     */
    public static function isLoggedIn(){
        return isset($_SESSION["is_logged_in"]) and $_SESSION["is_logged_in"];
    }
}
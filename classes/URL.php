<?php

class Url {

    /**
     *
     * Redirect to other url adress
     *
     * @param string $path - adress, where want to be redirected
     *
     * @return void
     *
     */
     public static function redirectUrl($path){
        // get if server is on https or http and if https is on
        if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] != "off"){
            $url_protocol = "https";
        } else {
            $url_protocol = "http";
        }

        // $_SERVER["HTTP_HOST] -> is actual localhost, for each location
        header("location: $url_protocol://". $_SERVER["HTTP_HOST"] . $path);
    }
}
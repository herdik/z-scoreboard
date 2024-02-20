<?php

require "../classes/Database.php";
require "../classes/Image.php";
require "../classes/Player.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

 // connection to Database
 $database = new Database();
 $connection = $database->connectionDB();

if ($_SERVER["REQUEST_METHOD"] === "GET"){

    if ((isset($_GET["player_Id"]) && isset($_GET["image_id"])) && (is_numeric($_GET["player_Id"]) && is_numeric($_GET["image_id"]))){
        $image_id = $_GET["image_id"];
        $user_id = $_GET["player_Id"];
        $image_name = Image::getImageName($connection, $image_id);
            
        $image_path = "../uploads/" . $user_id . "/" . $image_name;

        $deleted_physically_Image = Image::deleteImageFromDirectory($image_path);

        if ($deleted_physically_Image) {
            $deleted_Image_Database = Image::deleteImageFromDatabase($connection, $image_id);
            if ($deleted_Image_Database) {
                $number_of_images = count(Image::getAllImages($connection, $user_id));
                if ($number_of_images <= 1) {
                    Player::updatePlayerImage($connection, "no-photo-player", $user_id);
                }
                Url::redirectUrl("/z-scoreboard/admin/image-gallery.php?player_Id=$user_id");
            } else {
                echo "Obrázok nie je nájdený!!!";
            }
        } else {
            echo "Obrázok nie je nájdený!!!";
        }  
    }

} else {
    die("Nepovolený prístup!!!");
}


?>
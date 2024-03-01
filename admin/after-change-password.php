<?php

require "../classes/Database.php";
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


if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $player_Id = $_POST["player_Id"];
    $password = $_POST["password-player"];

    if (Player::updatePlayerPassword($connection, $password, $player_Id)) {
        Url::redirectUrl("/z-scoreboard/admin/player-profil.php?player_Id=$player_Id");
    } else {
        $not_updated_password = "Heslo sa nepodarilo zmeniť";
        Url::redirectUrl("/z-scoreboard/admin/logedin-error.php?logedin_error=$not_updated_password");
    } 
} else {
    echo "Nepovolený prístup";
}
?>
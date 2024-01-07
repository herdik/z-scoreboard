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
    $user_email = $_POST["user_email"];
    $first_name = $_POST["first_name"];
    $second_name = $_POST["second_name"];
    $country = $_POST["country"];
    $player_club = $_POST["player_club"];
    $player_Image = $_POST["player_Image"];
    $player_cue = $_POST["player_cue"];
    $player_break_cue = $_POST["player_break_cue"];
    $player_jump_cue = $_POST["player_jump_cue"];

    
    

    if (Player::updatePlayer($connection, $user_email, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue, $player_Id)){
        Url::redirectUrl("/z-scoreboard/admin/player-profil.php?player_Id=$player_Id");
    } else {
        echo "Hráča sa nepodarilo upraviť";
    }
} else {
    echo "Nepovolený prístup";
}
?>


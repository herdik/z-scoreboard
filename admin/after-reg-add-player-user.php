<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/Url.php";


if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $user_name = $_POST["user_name"];
    $first_name = $_POST["first_name"];
    $second_name = $_POST["second_name"];
    $country = $_POST["country"];
    $player_club = $_POST["player_club"];
    $player_Image = $_POST["player_Image"];
    $player_cue = $_POST["player_cue"];
    $player_break_cue = $_POST["player_break_cue"];
    $player_jump_cue = $_POST["player_jump_cue"];

    
    $player_id = Player::createPlayerUser($connection, $user_name, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue);

    if (!empty($player_id)){
        Url::redirectUrl("/z-scoreboard/admin/player-user-profil.php?player_Id=$player_id");
    } else {
        echo "Nového hráča sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>

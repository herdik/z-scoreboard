<?php

require "../classes/Database.php";
require "../classes/Player.php";


if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $user_name = $_POST["user_name"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $first_name = $_POST["first_name"];
    $second_name = $_POST["second_name"];
    $country = $_POST["country"];
    $player_club = $_POST["player_club"];
    $player_Image = $_POST["player_Image"];
    $player_cue = $_POST["player_cue"];
    $player_break_cue = $_POST["player_break_cue"];
    $player_jump_cue = $_POST["player_jump_cue"];
    $player_type = 'player';

    
    $player_id = Player::createPlayerUser($connection, $user_name, $password, $first_name, $second_name, $country, $player_club, $player_Image, $player_cue, $player_break_cue, $player_jump_cue, $player_type);

    if (!empty($player_id)){
        echo $player_id;
    } else {
        echo "Nového hráča sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}

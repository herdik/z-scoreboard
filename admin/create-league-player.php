<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
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

    $league_id = $_POST["league_id"];
    $player_Id = $_POST["player_Id"];
    
    $reg_player_in_league = LeaguePlayer::createLeaguePlayer($connection, $league_id, $player_Id);

    if ($reg_player_in_league){
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
        echo "Novú ligu sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>
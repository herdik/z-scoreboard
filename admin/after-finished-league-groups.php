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
    $players_in_group = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, false);

    var_dump($players_in_group);


    // if (!empty($league_id)){
    //     Url::redirectUrl("/z-scoreboard/admin/current-league.php?league_id=$league_id");
    // } else {
    //     echo "Novú ligu sa nepodarilo pridať";
    // }
} else {
    echo "Nepovolený prístup";
}
?>
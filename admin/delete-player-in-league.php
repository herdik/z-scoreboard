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

 // connection to Database
 $database = new Database();
 $connection = $database->connectionDB();

if (($_SERVER["REQUEST_METHOD"] === "GET") || ($_SERVER["REQUEST_METHOD"] === "POST")){

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $league_id = $_POST["league_id"];
        $player_Id = $_POST["player_Id"];
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
        if ((isset($_GET["player_Id"]) && isset($_GET["league_id"])) && (is_numeric($_GET["player_Id"]) && is_numeric($_GET["league_id"]))){
            $league_id = $_GET["league_id"];
            $player_Id = $_GET["player_Id"];
        }
    }

    $deleted_league_player = LeaguePlayer::deleteLeaguePlayer($connection, $league_id, $player_Id);

    if ($deleted_league_player) {
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
    echo "Hráč nie je nájdený!!!";
    }    
    
} else {
    die("Nepovolený prístup!!!");
}


?>
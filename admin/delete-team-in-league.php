<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeagueTeam.php";
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
        $team_id = $_POST["team_id"];
           
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
        if ((isset($_GET["team_id"]) && isset($_GET["league_id"])) && (is_numeric($_GET["team_id"]) && is_numeric($_GET["league_id"]))){
            $league_id = $_GET["league_id"];
            $team_id = $_GET["team_id"];
        }
    }

    $deleted_league_player = LeagueTeam::deleteLeagueTeam($connection, $league_id, $team_id);

    if ($deleted_league_player) {
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
    echo "Hráč nie je nájdený!!!";
    }    
    
} else {
    die("Nepovolený prístup!!!");
}


?>
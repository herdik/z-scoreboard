<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayerDoubles.php";
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
        // player_Id_doubles_1 - current logged in player after Authorization
        $player_Id_doubles = $_POST["player_Id_doubles_1"];
        $doubles_in_league_id = LeaguePlayerDoubles::getDoubles($connection, $league_id, $player_Id_doubles)["doubles_in_league_id"]; 
    } elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
        if ((isset($_GET["doubles_in_league_id"]) && isset($_GET["league_id"])) && (is_numeric($_GET["doubles_in_league_id"]) && is_numeric($_GET["league_id"]))){
            $league_id = $_GET["league_id"];
            $doubles_in_league_id = $_GET["doubles_in_league_id"];
        }
    }

    $deleted_league_doubles = LeaguePlayerDoubles::deleteSpecLeagueDoubles($connection, $league_id, $doubles_in_league_id);

    if ($deleted_league_doubles) {
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
    echo "Dvojica nie je nájdená!!!";
    }    
    
} else {
    die("Nepovolený prístup!!!");
}


?>
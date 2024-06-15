<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeaguePlayerDoubles.php";
require "../classes/League.php";
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
    $reg_player_in_league = FALSE;
    $league_id = $_POST["league_id"];
    $league_playing_format = League::getLeague($connection, $league_id)["playing_format"];

    

    
    if ($league_playing_format === "single"){

        $player_Id = $_POST["player_Id"];
    
        $reg_player_in_league = LeaguePlayer::createLeaguePlayer($connection, $league_id, $player_Id);

    } elseif ($league_playing_format === "doubles"){
        $player_Id_doubles_1 = $_POST["player_Id_doubles_1"];
        $player_Id_doubles_2 = $_POST["player_Id_doubles_2"];

        $reg_player_in_league = LeaguePlayerDoubles::createLeagueDoubles($connection, $league_id, $player_Id_doubles_1, $player_Id_doubles_2);

    }
    
    if ($reg_player_in_league){
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
        echo "Nového hráča/hráčov alebo team sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>
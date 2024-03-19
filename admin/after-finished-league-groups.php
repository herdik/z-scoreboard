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
    $number_of_groups_in_league = LeaguePlayer::getNumberOfGroups($connection, $league_id);

    $inactive_players = LeaguePlayer::getInactiveLeaguePlayers($connection, $league_id, 0);

    if (count($inactive_players) > 0){
        foreach($inactive_players as $one_inactive_player){
            $player_in_league_id = $one_inactive_player["player_in_league_id"];
            LeaguePlayer::deleteSpecLeaguePlayer($connection, $league_id, $player_in_league_id);
            
        }
    }
    
    for ($i = 1; $i <= $number_of_groups_in_league; $i++){
        $count_players_in_group = LeaguePlayer::countActiveLeaguePlayersInGroup($connection, $league_id, $i);
        if ($count_players_in_group % 2 != 0){
            LeaguePlayer::createLeaguePlayer($connection, $league_id, 0, $i);
        }
    }
     
    // if (!empty($league_id)){
    //     Url::redirectUrl("/z-scoreboard/admin/current-league.php?league_id=$league_id");
    // } else {
    //     echo "Novú ligu sa nepodarilo pridať";
    // }
} else {
    echo "Nepovolený prístup";
}
?>
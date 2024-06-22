<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeagueTeam.php";
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

    $league_id = $_POST["league_id"];
    $league_playing_format = League::getLeague($connection, $league_id)["playing_format"];
    $reg_player_or_team_in_league = array();

    if ($league_playing_format === "single"){
        $players_id = $_POST["selected_players_id"];
        
        foreach($players_id as $player_Id) {
            $one_reg_player_in_league = LeaguePlayer::createLeaguePlayer($connection, $league_id, $player_Id);
            $reg_player_or_team_in_league[] = $one_reg_player_in_league;

        }
    } elseif ($league_playing_format === "teams"){
        $teams_id = $_POST["selected_teams_id"];

        foreach($teams_id as $team_id) {
            $one_reg_player_in_league = LeagueTeam::createLeagueTeam($connection, $league_id, $team_id);
            $reg_player_or_team_in_league[] = $one_reg_player_in_league;

        }
    }
    
    
    if (in_array(true, $reg_player_or_team_in_league)){
        Url::redirectUrl("/z-scoreboard/admin/admin-list_of_league_players.php?league_id=$league_id");
    } else {
        echo "Novú ligu sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>
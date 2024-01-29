<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeagueSettings.php";
require "../classes/LeagueGroup.php";
// require "../classes/Url.php";


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
    $registered_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, "list_of_players_league.player_Id, list_of_players_league.league_id");

    $league_groups = LeagueSettings::getLeagueSettings($connection, $league_id);
    $number_of_groups = $league_groups["count_groups"];

    $random_league_groups = LeagueGroup::shuffleRandomGroups($registered_players, $number_of_groups, $league_id, $empty_player);
    var_dump($random_league_groups);

    

} else {
    echo "Ligové zápasy sa nenašli";
}
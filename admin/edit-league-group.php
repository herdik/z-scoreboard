<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeagueSettings.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

var_dump($_POST);


if ($_SERVER["REQUEST_METHOD"] === "GET" || $_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    // post are data by add new group for player in league
    // get are data by set 0 group for player in league - NO play in current league
    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $player_in_league_id = $_POST["player_in_league_id"];
        $league_group = $_POST["league_group"];
        $league_id = $_POST["league_id"];
    } else {
        if ((isset($_GET["player_in_league_id"]) && isset($_GET["league_id"]) && isset($_GET["league_group"])) && (is_numeric($_GET["player_in_league_id"]) && is_numeric($_GET["league_id"]) && is_numeric($_GET["league_group"]))){
            $player_in_league_id = $_GET["player_in_league_id"];
            $league_group = $_GET["league_group"];
            $league_id = $_GET["league_id"];
        }
    }
    
    // update specific group by delete player from league and added new group in league
    if (LeaguePlayer::updateSpecificLeagueGroup($connection, $league_group, $player_in_league_id, $league_id)){
        Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id");
    } else {
        $create_groups_error = "Hráč nebol odstránený zo skupiny";
        Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_groups_error");
    }
    
} else {
    echo "Ligové zápasy sa nenašli";
}
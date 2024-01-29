<?php

require "../classes/Database.php";
require "../classes/Player.php";
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

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $league_id = $_POST["league_id"];
    if ($_POST["rematch"] == null) {
        $_POST["rematch"] = "0";
    }
    $rematch = $_POST["rematch"];
    $race_to = $_POST["race_to"];
    $count_tables = $_POST["count_tables"];
    $count_groups = $_POST["count_groups"];


    $league_settings_exists = LeagueSettings::getLeagueSettings($connection, $league_id);

    
    if (!$league_settings_exists){
        
        $league_settings = LeagueSettings::createLeagueSettings($connection, $league_id, $rematch, $race_to, $count_tables, $count_groups);

        if (($league_settings)){
            Url::redirectUrl("/z-scoreboard/admin/league-settings.php?league_id=$league_id");
        } else {
            echo "Nové ligové nastavenia sa nepodarilo pridať";
        }

    } else {
        
        $update_league_settings = LeagueSettings::updateLeagueSettings($connection, $league_id, $rematch, $race_to, $count_tables, $count_groups);

        if ($update_league_settings){
            Url::redirectUrl("/z-scoreboard/admin/league-settings.php?league_id=$league_id");
        } else {
            echo "Update ligových nastavení sa nepodaril";
        }

    }
} else {
    echo "Nepovolený prístup";
}
?>
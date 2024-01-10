<?php

require "../classes/Database.php";
require "../classes/Player.php";
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

    $league_name = $_POST["league_name"];
    $category = $_POST["category"];
    $playing_format = $_POST["playing_format"];
    $date_of_event = $_POST["date_of_event"];
    $season = $_POST["season"];
    $discipline = intval($_POST["discipline"]);
    $venue = $_POST["venue"];
    $type = $_POST["type"];
    
    if (is_numeric($_SESSION["logged_in_user_id"]) and isset($_SESSION["logged_in_user_id"])){
        $user_info = Player::getUser($connection, $_SESSION["logged_in_user_id"]);
        $manager = htmlspecialchars($user_info["first_name"]). " " .htmlspecialchars($user_info["second_name"]);
    } else {
        $manager = "Nezistený";
    }
    
    
    $league_id = League::createLeague($connection, $league_name, $category, $playing_format, $date_of_event, $season, $discipline, $venue, $type, $manager);

    if (!empty($league_id)){
        Url::redirectUrl("/z-scoreboard/admin/current-league.php?league_id=$league_id");
    } else {
        echo "Novú ligu sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>
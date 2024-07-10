<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueMatch.php";
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

    $btn_value = $_POST["btn_value"];
    $match_id = $_POST["match_id"];
    $league_id = $_POST["league_id"];
    $league_group = $_POST["league_group"];

    $league_infos = League::getLeague($connection, $league_id);

    if ($btn_value === "Čaká"){
        $_SESSION["open_dialog"] = true;
        $_SESSION["match_id"] = $match_id;
        // $selected_league_match = LeagueMatch::getLeagueMatch($connection, $match_id, $columns = "*");
        // $open_dialog = true;
    } elseif ($btn_value === "Zapnúť"){
        $match_status = "match_waiting";
        $match_status_value = true;

        if ($league_infos["playing_format"] === "single"){
            $update_done = LeagueMatch::updateLeagueMatch($connection, $match_id, $btn_value);
        } elseif ($league_infos["playing_format"] === "doubles"){
            
        } elseif ($league_infos["playing_format"] === "teams"){
            
        }
    }

    
    
    

    if ($update_done || ((isset($_SESSION["open_dialog"]) && $_SESSION["open_dialog"]) && (isset($_SESSION["match_id"]) && $_SESSION["match_id"]))) {
        if (is_numeric($league_group) && $league_group == true){
            Url::redirectUrl("/z-scoreboard/admin/admin-league-matches-by-group.php?league_id=$league_id&league_group=$league_group");
        } else {
            Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id"); 
        }   
    } else {
    echo "Zápas sa nenašiel!!!";
    }    

} else {
    echo "Nepovolený prístup";
}
?>
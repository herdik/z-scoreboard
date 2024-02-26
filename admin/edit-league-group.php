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


if ($_SERVER["REQUEST_METHOD"] === "GET"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    // var_dump($_GET);
    var_dump(LeaguePlayer::getPlayerGroupInLeague($connection, $_GET["league_id"]));
    // if ((!in_array(false, $get_groups)) && (count($get_groups) > 0)){
    //     Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id");
    // } else {
    //     $create_groups_error = "Skupiny sa nevytvorili";
    //     Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_groups_error");
    // }

} else {
    echo "Ligové zápasy sa nenašli";
}
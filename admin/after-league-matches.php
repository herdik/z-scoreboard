<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeagueSettings.php";
require "../classes/LeagueGroup.php";
require "../classes/Url.php";


// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

$get_groups = array(true, true, true);

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $league_id = $_POST["league_id"];
    $registered_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, true, "list_of_players_league.player_Id, list_of_players_league.league_id");
    
    $empty_player = Player::getUserId($connection, "none");

    $league_groups = LeagueSettings::getLeagueSettings($connection, $league_id);
    $number_of_groups = $league_groups["count_groups"];

    $random_league_groups = LeagueGroup::shuffleRandomGroups($registered_players, $number_of_groups, $league_id, $empty_player);

    // update choosed random league_group for every player
    foreach ($random_league_groups as $league_group_nr=>$value) {
        
        foreach($random_league_groups[$league_group_nr] as $league_player) {
            if ($league_player["player_Id"] === 0) {
                $group_added = LeaguePlayer::createLeaguePlayer($connection, $league_id, $league_player["player_Id"], $league_group_nr + 1);
            } else {
                $group_added = LeaguePlayer::updateLeaguePlayer($connection, $league_group_nr + 1, $league_player["player_Id"], $league_id);
            }
            array_push($get_groups, $group_added);
        }
        
    }
    
    if ((!in_array(false, $get_groups)) && (count($get_groups) > 0)){
        Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id");
    } else {
        $create_groups_error = "Skupiny sa nevytvorili";
        Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_groups_error");
    }

} else {
    echo "Ligové zápasy sa nenašli";
}
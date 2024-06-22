<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeaguePlayerDoubles.php";
require "../classes/LeagueTeam.php";
require "../classes/LeagueSettings.php";
require "../classes/League.php";
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

$get_groups = array();

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    // database connection
    $database = new Database();
    $connection = $database->connectionDB();

    $league_id = $_POST["league_id"];
    $league_infos = League::getLeague($connection, $league_id);
    $empty_player = Player::getUserId($connection, "none");
    $league_groups = LeagueSettings::getLeagueSettings($connection, $league_id);
    $number_of_groups = $league_groups["count_groups"];

    if ($league_infos["playing_format"] === "single"){
        $registered_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, true, "list_of_players_league_single.player_Id, list_of_players_league_single.league_id");
    } elseif ($league_infos["playing_format"] === "doubles"){
        // $registered_players - means doubles
        $registered_players = LeaguePlayerDoubles::getAllLeagueDoubles($connection, $league_id, true, "list_of_players_league_doubles.player_Id_doubles_1, list_of_players_league_doubles.player_Id_doubles_2, list_of_players_league_doubles.league_id");
    } elseif ($league_infos["playing_format"] === "teams"){
        $registered_players = LeagueTeam::getAllLeagueTeams($connection, $league_id, true, "list_of_players_league_team.team_id, list_of_players_league_team.league_id");
    }
    

    $random_league_groups = LeagueGroup::shuffleRandomGroups($registered_players, $number_of_groups, $league_id, $empty_player);


    // update choosed random league_group for every player
    foreach ($random_league_groups as $league_group_nr=>$value) {
        
        foreach($random_league_groups[$league_group_nr] as $league_player) {
            if (isset($league_player["player_Id"]) && $league_player["player_Id"] === 0) {
                var_dump($league_player["player_Id"]);
                if ($league_infos["playing_format"] === "single"){
                    $group_added = LeaguePlayer::createLeaguePlayer($connection, $league_id, $league_player["player_Id"], $league_group_nr + 1);
                } elseif ($league_infos["playing_format"] === "doubles"){
                    $group_added = LeaguePlayerDoubles::createLeagueDoubles($connection, $league_id, $league_player["player_Id"], $league_player["player_Id"], $league_group_nr + 1);
                } elseif ($league_infos["playing_format"] === "teams"){
                    $group_added = LeagueTeam::createLeagueTeam($connection, $league_id, $league_player["player_Id"], $league_group_nr + 1);
                }
            } else {

                if ($league_infos["playing_format"] === "single"){
                    $group_added = LeaguePlayer::updateLeaguePlayer($connection, $league_group_nr + 1, $league_player["player_Id"], $league_id);
                } elseif ($league_infos["playing_format"] === "doubles"){
                    $group_added = LeaguePlayerDoubles::updateLeagueDoubles($connection, $league_group_nr + 1, $league_player["player_Id_doubles_1"], $league_player["player_Id_doubles_2"], $league_id);
                } elseif ($league_infos["playing_format"] === "teams"){
                    $group_added = LeagueTeam::updateLeagueTeam($connection, $league_group_nr + 1, $league_player["team_id"], $league_id);
                }
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
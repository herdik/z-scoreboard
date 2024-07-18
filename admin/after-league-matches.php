<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeaguePlayerDoubles.php";
require "../classes/LeagueTeam.php";
require "../classes/LeagueSettings.php";
require "../classes/LeagueMatch.php";
require "../classes/League.php";
require "../classes/LeagueTable.php";
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

    // League playing settings
    $league_id = $_POST["league_id"];
    $league_infos = League::getLeague($connection, $league_id);
    $rematch = LeagueSettings::getLeagueSettings($connection, $league_id, "rematch");
    $choosed_game = $league_infos["discipline"];

    if ($league_infos["playing_format"] === "single"){
        LeaguePlayer::deleteLeaguePlayer($connection, $league_id, 0);
        $number_of_groups_in_league = LeaguePlayer::getNumberOfGroups($connection, $league_id);
        $inactive_players = LeaguePlayer::getInactiveLeaguePlayers($connection, $league_id, 0);
    } elseif ($league_infos["playing_format"] === "doubles"){
        LeaguePlayerDoubles::deleteLeagueDobles($connection, $league_id, 0, 0);
        $number_of_groups_in_league = LeaguePlayerDoubles::getNumberOfGroups($connection, $league_id);
        $inactive_players = LeaguePlayerDoubles::getInactiveLeagueDoubles($connection, $league_id, 0);
    } elseif ($league_infos["playing_format"] === "teams"){
        LeagueTeam::deleteLeagueTeam($connection, $league_id, 0);
        $number_of_groups_in_league = LeagueTeam::getNumberOfGroups($connection, $league_id);
        $inactive_players = LeagueTeam::getInactiveLeagueTeams($connection, $league_id, 0);
    }
    
    

    // Array with number of rounds in every group
    $number_of_rounds_in_group = array();
    // Array with number of matches in every group
    $matches_in_rounds_by_group = array();

    // ********** if user deleted player - make change in database for current league START **********
    // delete all players who are not in groups
    if (count($inactive_players) > 0){
        foreach($inactive_players as $one_inactive_player){
            if ($league_infos["playing_format"] === "single"){
                $player_in_league_id = $one_inactive_player["player_in_league_id"];
                LeaguePlayer::deleteSpecLeaguePlayer($connection, $league_id, $player_in_league_id);
            } elseif ($league_infos["playing_format"] === "doubles"){
                $doubles_in_league_id = $one_inactive_player["doubles_in_league_id"];
                LeaguePlayerDoubles::deleteSpecLeagueDoubles($connection, $league_id, $doubles_in_league_id);
            } elseif ($league_infos["playing_format"] === "teams"){
                $team_in_league_id = $one_inactive_player["team_in_league_id"];
                LeagueTeam::deleteSpecLeagueTeam($connection, $league_id, $team_in_league_id);
            }
        }
    }
    // ********** if user deleted player - make change in database for current league FINISH **********


    for ($i = 0; $i < $number_of_groups_in_league; $i++){
        if ($league_infos["playing_format"] === "single"){
            $number_of_players_in_group = LeaguePlayer::countActiveLeaguePlayersInGroup($connection, $league_id, $i + 1);
        } elseif ($league_infos["playing_format"] === "doubles"){
            $number_of_players_in_group = LeaguePlayerDoubles::countActiveLeagueDoublesInGroup($connection, $league_id, $i + 1);
        } elseif ($league_infos["playing_format"] === "teams"){
            $number_of_players_in_group = LeagueTeam::countActiveLeagueTeamsInGroup($connection, $league_id, $i + 1);
        }

        if ($number_of_players_in_group % 2 != 0){

            if ($league_infos["playing_format"] === "single"){
                // delete players 0 to prevent multiple zero players in league
                LeaguePlayer::createLeaguePlayer($connection, $league_id, 0, $i + 1);
                $number_of_players_in_group = LeaguePlayer::countActiveLeaguePlayersInGroup($connection, $league_id, $i + 1);

            } elseif ($league_infos["playing_format"] === "doubles"){
                // delete doubles 0 to prevent multiple zero players in league
                LeaguePlayerDoubles::createLeagueDoubles($connection, $league_id, 0, 0, $i + 1);
                $number_of_players_in_group = LeaguePlayerDoubles::countActiveLeagueDoublesInGroup($connection, $league_id, $i + 1);
            } elseif ($league_infos["playing_format"] === "teams"){
                // delete players 0 to prevent multiple zero players in league
                LeagueTeam::createLeagueTeam($connection, $league_id, 0, $i + 1);
                $number_of_players_in_group = LeagueTeam::countActiveLeagueTeamsInGroup($connection, $league_id, $i + 1);
            }
        } 
        
        // elseif ($number_of_players_in_group === 2){

        //     if ($league_infos["playing_format"] === "single"){

        //         LeaguePlayer::createLeaguePlayer($connection, $league_id, 0, $i + 1);
        //         LeaguePlayer::createLeaguePlayer($connection, $league_id, 0, $i + 1);
        //         $number_of_players_in_group = LeaguePlayer::countActiveLeaguePlayersInGroup($connection, $league_id, $i + 1);

        //     } elseif ($league_infos["playing_format"] === "doubles"){
        //         LeaguePlayerDoubles::createLeagueDoubles($connection, $league_id, 0, 0, $i + 1);
        //         LeaguePlayerDoubles::createLeagueDoubles($connection, $league_id, 0, 0, $i + 1);
        //         $number_of_players_in_group = LeaguePlayerDoubles::countActiveLeagueDoublesInGroup($connection, $league_id, $i + 1);
        //     }
        // }

        // League playing settings
        $one_round_in_group = $number_of_players_in_group - 1;
        $matches_in_round = $number_of_players_in_group / 2;

        // Push number of round for one group in array
        $number_of_rounds_in_group[] = $one_round_in_group;

        // Push number of matches for one round for each group in array
        $matches_in_rounds_by_group[] = $matches_in_round;

    }

    // Update number of groups because user can change groups by manually inserting player to group i current league
    $basic_league_settings = LeagueSettings::getLeagueSettings($connection, $league_id, $columns = "*");

    $update_league_settings = LeagueSettings::updateLeagueSettings($connection, $league_id, boolval($rematch[0]), $basic_league_settings["race_to"], $basic_league_settings["count_tables"], $number_of_groups_in_league);
    
    // Current league settings prepared for League
    $current_league_settings = LeagueSettings::getLeagueSettings($connection, $league_id, $columns = "*");
    
    // all players from current league with spesific informations according League group - selected random from group - $player_in_group
    $league_done = array();
    for ($i = 0; $i < $number_of_groups_in_league; $i++){
        $group = $i + 1;
        if ($league_infos["playing_format"] === "single"){
            $player_in_group = LeaguePlayer::getAllLeaguePlayersByGroup($connection, $league_id, $group);
        } elseif ($league_infos["playing_format"] === "doubles"){
            $player_in_group = LeaguePlayerDoubles::getAllLeagueDoublesByGroup($connection, $league_id, $group);
        } elseif ($league_infos["playing_format"] === "teams"){
            $player_in_group = LeagueTeam::getAllLeagueTeamsByGroup($connection, $league_id, $group);
        }
        if (count($player_in_group) > 1){
            $group_finished = LeagueMatch::createLeagueMatch($connection, $current_league_settings["rematch"], $number_of_rounds_in_group[$i], $matches_in_rounds_by_group[$i], $player_in_group, $league_id, $choosed_game, $group, $league_infos["playing_format"]);
            $league_done[] = $group_finished;
        }
        
    }

    if(count(array_unique($league_done)) === 1){
        // true
        if(current($league_done)){
            $active_league = League::updateLeague($connection, $league_id, true);

            $league_table_done = array();

            // if league is active - create results table for current league according groups
            if ($league_infos["playing_format"] === "single"){
                $all_active_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, true);

                // create league table for current league - start
                foreach ($all_active_players as $one_active_player){
                    $row_league_table = LeagueTable::createLeagueTable($connection, $one_active_player["league_id"], $one_active_player["league_group"], $one_active_player["player_Id"]);
                    $league_table_done[] = $row_league_table;
                }

                // create league table false - print error message
                if(count(array_unique($league_table_done)) > 1){
                    $create_league_table_error = "Ligová tabuľka sa nevytvorila";
                    Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_league_table_error");
                } else {
                    if(!current($league_done)){
                        $create_league_table_error = "Ligová tabuľka sa nevytvorila";
                        Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_league_table_error");
                    }
                }
            }
        }
        
    } else {
        $create_league_matches_error = "Ligové zápasy sa nevytvorili";
        Url::redirectUrl("/z-scoreboard/errors/error-page.php?in_error=$create_league_matches_error");
    }
    
        
    if (!empty($league_id)){
        Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id");
    } else {
        echo "Ligové zápasy sa nepodarilo pridať";
    }
} else {
    echo "Nepovolený prístup";
}
?>
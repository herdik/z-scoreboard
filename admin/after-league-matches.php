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
require "../classes/LeagueTableDoubles.php";
require "../classes/UndermatchSingle.php";
require "../classes/UndermatchDoubles.php";
require "../classes/OptionSingle.php";
require "../classes/OptionDoubles.php";
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

    
    // $current_league_teams = LeagueTeam::getAllLeagueTeams($connection, $league_id, true);
    // // var_dump($current_league_teams);
    // foreach ($current_league_teams as $current_league_team){
    //     $current_team_id = $current_league_team["team_id"];
    //     $players_according_team = Player::getAllPlayersByTeam($connection, $current_team_id, "player_Id, first_name, second_name, country, player_club");
    //     for ($i=0; $i < count($players_according_team); $i++){
    //         // creating one single option for team in team match
    //         var_dump($players_according_team[$i]["player_Id"]);
    //         var_dump("jednotlivec");
    //         for ($y=0; $y < count($players_according_team); $y++){
    //             if ($y > $i){
    //                 if ($i != $y){
    //                     // creating one doubles option for team in team match
    //                     var_dump($players_according_team[$i]["player_Id"]);
    //                     var_dump($players_according_team[$y]["player_Id"]);
    //                     var_dump("dvojica");
    //                 }
    //             }
    //         }
    //     };
    // }



    // $league_matches_by_group = LeagueMatch::getAllLeagueMatches($connection, 51, 1, $league_infos["playing_format"]);
    // var_dump($league_matches_by_group[0]);



    // $current_match_id = $league_matches_by_group[0]["match_id"];
    // $current_league_group = $league_matches_by_group[0]["league_group"];
    // $current_team_id_1 = $league_matches_by_group[0]["team_id_1"];
    // $current_team_id_2 = $league_matches_by_group[0]["team_id_2"];
    
    

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

            // ********undermatches for teams********* 
            if ($league_infos["playing_format"] === "teams"){
                for ($i = 0; $i < $number_of_groups_in_league; $i++){
                    $group = $i + 1;
                    $league_matches_by_group = LeagueMatch::getAllLeagueMatches($connection, $league_id, $group, $league_infos["playing_format"]);

                    // loop all team leagumatches accordiing group to set every team match - 9 basic undermatches/team_matches 
                    for ($team_match = 0; $team_match < count($league_matches_by_group); $team_match++){

                        for ($team_index=0; $team_index < 2; $team_index++){
                            
                            $current_id = $team_index + 1;

                            $current_match_id = $league_matches_by_group[$team_match]["match_id"];
                            $current_league_group = $league_matches_by_group[$team_match]["league_group"];
                            // loop take team_id_1 and 2 from every team match
                            $current_team_id = $league_matches_by_group[$team_match]["team_id_$current_id"];
                            
                            $players_according_team = Player::getAllPlayersByTeam($connection, $current_team_id, "player_Id, first_name, second_name, country, player_club");

                            // creatinig options single and doubles for team in team match
                            for ($i=0; $i < count($players_according_team); $i++){
                                // creating one single option for team in team match
                                $current_option_single = OptionSingle::createOptionSingle($connection, $current_match_id, $league_id, $current_league_group, $players_according_team[$i]["player_Id"], $current_team_id, true);
                                
                                for ($y=0; $y < count($players_according_team); $y++){
                                    if ($y > $i){
                                        if ($i != $y){
                                            // creating one doubles option for team in team match
                                            $current_option_doubles = OptionDoubles::createOptionDoubles($connection, $current_match_id, $league_id, $current_league_group, $players_according_team[$i]["player_Id"], $players_according_team[$y]["player_Id"], $current_team_id, true);
                                            
                                        }
                                    }
                                }
                            };
                        }

                        // creating 9 undermatches for every match in league
                        for ($sub_match = 0; $sub_match < 9; $sub_match++) {
            
                            $playerInMatch = $sub_match % 2 === 0 || $sub_match > 5 ? "single" : "doubles";
                            if (($sub_match >= 2 && $sub_match < 4) || $sub_match >= 6) {
                                $typeOfGame = 8;
                            } elseif ($sub_match >= 4) {
                                $typeOfGame = 9;
                            } else {
                                $typeOfGame = 10;
                            }
            
                            // basic undermatches/team_matches
                            if ($playerInMatch === "single") {
                            $teams_undermatch = UndermatchSingle::createLeagueUnderMatch($connection, $sub_match + 1, $league_matches_by_group[$team_match]["match_id"], $league_matches_by_group[$team_match]["league_id"], $league_matches_by_group[$team_match]["league_group"], 0, 0, 0, 0, $typeOfGame, 0, false, false, false, false, "single");
                            } elseif ($playerInMatch === "doubles") {
                                $teams_undermatch = UndermatchDoubles::createLeagueUnderMatch($connection, $sub_match + 1, $league_matches_by_group[$team_match]["match_id"], $league_matches_by_group[$team_match]["league_id"], $league_matches_by_group[$team_match]["league_group"], 0, 0, 0, 0, 0, 0, $typeOfGame, 0, false, false, false, false, "doubles");
                            }
                        }
                    }
                }        
            }
            // ********undermatches for teams********* 

            $league_table_done = array();

            // if league is active - create results table for current league according groups
            if ($league_infos["playing_format"] === "single"){
                $all_active_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, true);

                // create league table for current league - start
                foreach ($all_active_players as $one_active_player){
                    $row_league_table = LeagueTable::createLeagueTable($connection, $one_active_player["league_id"], $one_active_player["league_group"], $one_active_player["player_Id"]);
                    $league_table_done[] = $row_league_table;
                }
            } elseif ($league_infos["playing_format"] === "doubles"){
                $all_active_players = LeaguePlayerDoubles::getAllLeagueDoubles($connection, $league_id, true);

                // create league table for current league - start
                foreach ($all_active_players as $one_active_player){
                    $row_league_table = LeagueTableDoubles::createLeagueTableDoubles($connection, $one_active_player["league_id"], $one_active_player["league_group"], $one_active_player["player_Id_doubles_1"], $one_active_player["player_Id_doubles_2"]);
                    $league_table_done[] = $row_league_table;
                }
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
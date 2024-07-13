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

    if (isset($_POST['click_view_btn'])){

        $arrayresult = [];

        $match_id = $_POST["match_id"];
        $league_id = $_POST["league_id"];

        $league_infos = League::getLeague($connection, $league_id);

        $selected_league_match = LeagueMatch::getLeagueMatch($connection, $match_id, $league_infos["playing_format"]);

        array_push($arrayresult, $selected_league_match);
        header('content-type: appliacation/json');
        echo json_encode($arrayresult);


        
        
    }

    if (isset($_POST['saveMatch'])){

        $match_id = $_POST["match_id"];
        $league_id = 43;
        $score_1 = $_POST["score_1"];
        
        $league_infos = League::getLeague($connection, $league_id);
        
        if ($league_infos["playing_format"] === "single"){
            $update_done = LeagueMatch::updateLeagueMatch($connection, $match_id, $score_1);
        } elseif ($league_infos["playing_format"] === "doubles"){
                
        } elseif ($league_infos["playing_format"] === "teams"){
                
        }

        if ($update_done){
            Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id"); 
        }

    }

    // if (isset($_POST['save_data_btn'])){

    //     $arrayresult = [];

    //     $match_id = $_POST["match_id"];
    //     $league_id = 43;
    //     $score_1 = 11;
        
    //     $league_infos = League::getLeague($connection, $league_id);
        
    //     if ($league_infos["playing_format"] === "single"){
    //         $update_done = LeagueMatch::updateLeagueMatch($connection, $match_id, $score_1);

    //         $selected_league_match = LeagueMatch::getLeagueMatch($connection, $match_id, $league_infos["playing_format"]);

    //         array_push($arrayresult, $selected_league_match);
    //         header('content-type: appliacation/json');
    //         echo json_encode($arrayresult);
    //     } elseif ($league_infos["playing_format"] === "doubles"){
                
    //     } elseif ($league_infos["playing_format"] === "teams"){
                
    //     }

    //     // if ($update_done){
    //     //     Url::redirectUrl("/z-scoreboard/admin/admin-league-matches.php?league_id=$league_id"); 
    //     // }

    // }
  

} else {
    echo "Nepovolený prístup";
}
?>
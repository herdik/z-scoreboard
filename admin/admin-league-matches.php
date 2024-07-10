<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeaguePlayerDoubles.php";
require "../classes/LeagueTeam.php";
require "../classes/LeagueMatch.php";



// verifying by session if visitor have access to this website
require "../classes/Authorization.php";
// get session
session_start();
// authorization for visitor - if has access to website 
if (!Auth::isLoggedIn()){
    die ("nepovolený prístup");
}

// database connection
$database = new Database();
$connection = $database->connectionDB();

if ($_SERVER["REQUEST_METHOD"] === "GET"){
    if (isset($_GET["league_id"]) and is_numeric($_GET["league_id"])){
        $league_infos = League::getLeague($connection, $_GET["league_id"]);
        $league_id = $_GET["league_id"];
        $league_group = 0;
        $active_league = $league_infos["active_league"];
        // if count groups are false, settings for league are not saved to database
        $count_groups = LeagueSettings::getLeagueSettings($connection, $league_id, "count_groups");
        $league_matches = LeagueMatch::getAllLeagueMatches($connection, $league_id, 1, $league_infos["playing_format"]);

        
        if ($league_infos["playing_format"] === "single"){
            $players_in_group = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, false);
            $players_without_group = LeaguePlayer::getPlayerGroupInLeague($connection, $league_id);
        } elseif ($league_infos["playing_format"] === "doubles"){
            $players_in_group = LeaguePlayerDoubles::getAllLeagueDoubles($connection, $league_id, false);
            $players_without_group = LeaguePlayerDoubles::getDoublesGroupInLeague($connection, $league_id);
        } elseif ($league_infos["playing_format"] === "teams"){
            $players_in_group = LeagueTeam::getAllLeagueTeams($connection, $league_id, false);
            $players_without_group = LeagueTeam::getTeamGroupInLeague($connection, $league_id);
        }
        
    } else {
        $league_infos = null;
        $registered_players = null;
        $count_groups = null;
        $players_without_group = null;
        $players_in_group = null;
        $active_league = false;
        $league_group = null;
    }

    if ((isset($_SESSION["open_dialog"]) && $_SESSION["open_dialog"]) && (isset($_SESSION["match_id"]) && $_SESSION["match_id"])) {
        $match_id = $_SESSION["match_id"];
        $selected_league_match = LeagueMatch::getLeagueMatch($connection, $match_id, $league_infos["playing_format"]);
        $open_dialog = true;
        unset($_SESSION["open_dialog"]);
        unset($_SESSION["match_id"]);
    } else {
        $open_dialog = false;
    }

} else {
    echo "Nepovolený prístup";
}

$group_nr = 0;

// for print current round nr in  --- > <div class="leagueRound"> 
$round_nr = 1;
// counter to add last </div> for <div class="leagueRound">
$counter = 1;
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoznam súťaží - administrácia</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/league-matches.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php if ($open_dialog): ?>

        <?php $open_dialog = false; ?>
        
        <!-- Modal okno pre editácia ligového zápasu -->
        <dialog id="modal">
            <script>
                function closeModal() {
                    document.querySelector("#modal").close()
                }
            </script>
            <form id="matchForm" action="" method="post">
                <h1>Ligový zápas</h1>
                <input type="checkbox" id="checkFinish" name="checkFinish">
                <label for="checkFinish">Ukončiť zápas</label>
                <div class="matchInfo">
                    <div class="modal-players">
                        <span class="pl1-span"><?= htmlspecialchars($selected_league_match["player1_firstname"]) . " " . htmlspecialchars($selected_league_match["player1_second_name"]) ?></span>
                        <input type="number" class="pl1-label" min="0" value="<?= htmlspecialchars($selected_league_match["score_1"]) ?>" step="1" name="score1">
                    </div>
                    
                    <input type="hidden" name="match_id" value="<?= htmlspecialchars($match_id) ?>">
                    <input type="submit" id="saveBtn"n value="Uložiť" name="saveMatch" onclick="return closeModal();">

                    <div class="modal-players">
                        <input type="number" class="pl2-label" min="0" value="<?= htmlspecialchars($selected_league_match["score_2"]) ?>" step="1" name="score2">
                        <span class="pl2-span"><?= htmlspecialchars($selected_league_match["player2_firstname"]) . " " . htmlspecialchars($selected_league_match["player2_second_name"]) ?></span>
                    </div>
                </div>
                <div class="chooseTable">
                    <div class="tableName">
                        Výber stola
                    </div>
                    
                    <div class="wrapper">
                        <select name="tableOptions" id="" class="table-options" onfocus='this.size=3;'
                        onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                            <option value="">1</option>
                            <option value="">2</option>
                            <option value="">3</option>
                            <option value="">4</option>
                            <option value="">5</option>
                            <option value="">6</option>
                            <option value="">7</option>
                            <option value="">8</option>
                            <option value="">9</option>
                            <option value="">10</option>
                            <option value="">11</option>
                            <option value="">12</option>
                        </select>
                    </div>

                </div>
                
            </form>
        </dialog>
        
        <script>
            openModal()
            function openModal() {
                document.querySelector("#modal").showModal()
                // let target = event.target;
                // console.log(target.parentElement)
            }
            
        </script>
        <!-- Modal okno pre editácia ligového zápasu -->
    <?php endif ?>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>
        <!-- SIDE NAV BAR -->
        <section class="navigation-bar">
            <ul>
                <li><a href="./current-league.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Informácie</a></li>
                <li><a href="./admin-list_of_league_players.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Zoznam hráčov</a></li>
                <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <li><a href="./league-settings.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Nastavenia</a></li>
                <?php endif; ?>
                <li><a href="./admin-league-matches.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Ligové zápasy</a></li>
                <li><a href="#">Výsledky</a></li>
            </ul>

        </section>
        
        <!-- MAIN LEAGUE CONTENT -->
        <section class="league-content">
            
            <!-- Structure for active league if all settings are used and league match are created -->
            <?php if ($active_league): ?>
            
                <div class="league-match-container">

                <!-- OVERVIEW WHEN IS MORE THAN ONE GROUP IN CURRENT LEAGUE -->
                <?php if ($count_groups["count_groups"] > 1): ?>
                    <div class="registered-groups">
                        <h2><?= htmlspecialchars($league_infos["league_name"]) ?></h2>
                        

                        <div class="all-groups">

                            <?php for($group = 1; $group <= $count_groups["count_groups"]; $group++): ?>
                                <article class="group-profil">

                                    <div class="picture-part1" style="
                                            background: url(../img/sbiz.jpg);
                                            background-size: cover;
                                            background-position: center;
                                            background-repeat: no-repeat;
                                            ">
                                
                            
                                        <div class="picture-part2" style="
                                            background: url(../img/black-logo-manila.png);
                                            background-size: cover;
                                            background-position: center;
                                            background-repeat: no-repeat;
                                            ">
                                        </div>
                                    </div>
                                    <h6 class="group-nr"><?php echo "Skupina č. " . $group?></h6>
                                
                                    <a class="group-infos" href="./admin-league-matches-by-group.php?league_id=<?= htmlspecialchars($league_id) ?>&league_group=<?= htmlspecialchars($group) ?>">Zápasy</a>

                                </article>
                            <?php endfor ?>
                        </div>  
                    </div>   
                <?php else: ?>
                <!-- OVERVIEW WHEN ONE GROUP IN CURRENT LEAGUE AND SHOW ALL LEAGUE MATCHES-->

                        <!-- Názov heading Liga -->
                        <div class="leagueHeading">
                            <h1><?= htmlspecialchars($league_infos["league_name"]) ?>
                                <!-- <span class="left-icon"></span>
                                <span class="right-icon"></span> -->
                            </h1> 
                        </div>

                        <!-- Liga Rozpis -->
                        <div class="league-matches show-league-matches">
                        
                        <?php foreach ($league_matches as $league_match): ?>

                            <?php if ($round_nr === $league_match["round_number"]): ?> 
                            
                            <?php if ($league_match["round_number"] > 1): ?>
                            </div>
                            <?php endif ?>

                            <div class="leagueRound">
                                <h1><?= $round_nr . ".kolo" ?></h1>
                                <?php $round_nr++ ?>
                            <?php endif ?>
                                

                                <div class="matchInformation">
                                    
                                <!-- Affects background-color, font color, table nr and button text according match status based on Database -->

                                <?php if ($league_match["match_finished"]): ?>

                                    <?php $button_text = "Ukončiť" ?>
                                    <?php $match_color = "fisnishedLeagueMatch" ?>
                                    <?php $table_font_color = "color:rgb(255, 255, 255)" ?>
                                    <?php $table_nr = "X" ?>

                                <?php elseif ($league_match["match_waiting"]): ?>

                                    <?php $button_text = "Čaká" ?>
                                    <?php $match_color = "waitingLeagueMatch" ?>
                                    <?php $table_font_color = "color:rgb(255, 255, 255)" ?>
                                    <?php $table_nr = "-" ?>

                                <?php elseif (!$league_match["match_started"]): ?> 

                                    <?php $button_text = "Zapnúť" ?>
                                    <?php $match_color = "" ?>
                                    <?php $table_font_color = "color:rgb(0, 0, 0)" ?>

                                    <?php $table_nr = "-" ?>

                                <?php endif ?>    

                                    <div class="tableNr <?= htmlspecialchars($match_color) ?>">
                                        <h3 style= "<?= htmlspecialchars($table_font_color) ?>"><?= htmlspecialchars($table_nr) ?></h3>
                                    </div>

                                    <!-- DISPLAY -> match rules and settings based on single double and teams-->
                                    <?php if ($league_infos["playing_format"] === "single"): ?>
                                        <?php require "../assets/league_match_single.php" ?> 

                                    <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                        <?php require "../assets/league_match_doubles.php" ?>                      
                                    <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                                        <?php require "../assets/league_match_teams.php" ?>            
                                    <?php endif ?>


                                </div>

                            <?php if (count($league_matches) === $counter): ?>    
                            </div>
                            <?php endif ?>

                            <?php endforeach ?>
                        </div>
                       
                <?php endif; ?>
                </div>

            <!-- Structure for CREATING LEAGUE - STEP BY STEP TO CREATE LEAGUE MATCHES && Structure for *NOT* active league -->
            <?php else: ?>
                
                <!-- BASIC SETTINGS TO CREATE LEAGUE - start -->
                <?php if (($players_without_group != 0) || (count($players_in_group) === 0)): ?>
                    
                <div class="main-container-settings">
                    
                    <!-- No players are registered to current league or league settings are not confirmed-->
                    <?php if ((count($players_in_group) === 0) || ($count_groups != true)): ?>
                        <h1>Nedostatočný počet prihlásených hráčov alebo nepotvrdené nastavenia ligy</h1>
                    <?php else: ?>

                        <?php if ($count_groups["count_groups"] > 1): ?> 
                        <h1>Liga skupiny</h1>
                        <?php else: ?>
                        <h1>Liga skupina</h1>
                        <?php endif; ?>
                        
                        <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                            <form id="create-groups" action="./after-finished-league-groups.php" method="post">
                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                                
                                <input type="submit" value="Vytvoriť">  

                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <!-- BASIC SETTINGS TO CREATE LEAGUE - finish -->
                
                <!-- ADVANCED SETTINGS TO CREATE LEAGUE ACCORDING GROUPS -start -->
                <?php else: ?>
                    
                <div class="main-container-groups">
                    <div class="basic-groups">

                    <!-- SHOW GROUPS ACCORDING TO LEAGUE ID -->
                    <?php foreach ($players_in_group as $one_player): ?>
                        <?php if($one_player["league_group"] === 0): ?>
                        <?php elseif($one_player["league_group"] === $group_nr): ?>
                            <tr>
                            <?php $table_nr++ ?>
                            <?php if ($league_infos["playing_format"] === "single"): ?>
                                <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?>
                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["country"]) ?>.png" alt="">
                                <span class="player-club"><?= htmlspecialchars($one_player["player_club"]) ?></span>
                                <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                </td>
                            <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                <td class="player-table"><p><?php echo $table_nr . "." ?></p><?= htmlspecialchars($one_player["player1_first_name"]) . " " . htmlspecialchars($one_player["player1_second_name"])?>
                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player1_country"]) ?>.png" alt="">
                                <span class="player-club"><?= htmlspecialchars($one_player["player1_club"]) ?></span><br>
                                <?= htmlspecialchars($one_player["player2_first_name"]) . " " . htmlspecialchars($one_player["player2_second_name"])?>
                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player2_country"]) ?>.png" alt="">
                                <span class="player-club"><?= htmlspecialchars($one_player["player2_club"]) ?></span>
                                <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["doubles_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                </td>
                            <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                                <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["team_name"]) ?>
                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["team_country"]) ?>.png" alt="">
                                <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["team_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                </td>
                            <?php endif ?>
                            </tr>
                            
                        
                        <?php else: ?>
                            <?php $group_nr++ ?>
                        </table>
                            <table class="one-modified-group">
                                <thead>
                                    <tr>
                                        <th>Skupina č.<?= htmlspecialchars($one_player["league_group"]) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $table_nr = 1; ?>
                                    <tr>

                                    <?php if ($league_infos["playing_format"] === "single"): ?>
                                        <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?>
                                        <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["country"]) ?>.png" alt="">
                                        <span class="player-club"><?= htmlspecialchars($one_player["player_club"]) ?></span>
                                        <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                        </td>
                                    <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                        <td class="player-table"><p><?php echo $table_nr . "." ?></p><?= htmlspecialchars($one_player["player1_first_name"]) . " " . htmlspecialchars($one_player["player1_second_name"])?>
                                        <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player1_country"]) ?>.png" alt="">
                                        <span class="player-club"><?= htmlspecialchars($one_player["player1_club"]) ?></span><br>
                                        <?= htmlspecialchars($one_player["player2_first_name"]) . " " . htmlspecialchars($one_player["player2_second_name"])?>
                                        <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player2_country"]) ?>.png" alt="">
                                        <span class="player-club"><?= htmlspecialchars($one_player["player2_club"]) ?></span>
                                        <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["doubles_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                        </td>
                                    <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                                        <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["team_name"]) ?>
                                        <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["team_country"]) ?>.png" alt="">
                                        <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["team_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                                        </td>
                                    <?php endif ?>
                                    </tr>
                                

                        <?php endif ?>
                    <?php endforeach ?>
                                </tbody>
                        </table> 
                        <!-- SHOW GROUP ACCORDING TO LEAGUE ID - PLAYERS whose will not play in this league-->   
                        <table class="one-modified-group">
                            <thead>
                                <tr>
                                    <th>Nezaradení</th>
                                </tr>
                            <thead>
                            <tbody>
                                <?php $unclassified_table_nr = 1; ?>
                                <?php foreach ($players_in_group as $one_player): ?>
                                    <?php if($one_player["league_group"] === 0): ?>
                                        <tr>

                                            <form action="./edit-league-group.php" method="POST">

                                            <?php if ($league_infos["playing_format"] === "single"): ?>   
                                                <td class="undefined"><?= $unclassified_table_nr .". ". htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?>
                                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["country"]) ?>.png" alt="">
                                                <span class="player-club"><?= htmlspecialchars($one_player["player_club"]) ?></span>
                                                <span class="choosed-group-OK">
                                                <input type="hidden" name="player_in_league_id" value="<?= htmlspecialchars($one_player["player_in_league_id"]) ?>">
                                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($one_player["league_id"]) ?>">
                                                <input type="number" name="league_group" class="select-groups" value="0" min="0" max="<?= htmlspecialchars($count_groups["count_groups"]) ?>">
                                                <button id="submit-group">OK</button>
                                                </span>
                                                </td>
                                            <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                                <td class="undefined"><span class="undefined-number"><?= $unclassified_table_nr . "." ?></span><br>
                                                <?= htmlspecialchars($one_player["player1_first_name"]) . " " . htmlspecialchars($one_player["player1_second_name"])?>
                                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player1_country"]) ?>.png" alt="">
                                                <span class="player-club"><?= htmlspecialchars($one_player["player1_club"]) ?></span><br>
                                                <?= htmlspecialchars($one_player["player2_first_name"]) . " " . htmlspecialchars($one_player["player2_second_name"])?>
                                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["player2_country"]) ?>.png" alt="">
                                                <span class="player-club"><?= htmlspecialchars($one_player["player2_club"]) ?></span>
                                                <span class="choosed-group-OK">
                                                <input type="hidden" name="player_in_league_id" value="<?= htmlspecialchars($one_player["doubles_in_league_id"]) ?>">
                                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($one_player["league_id"]) ?>">
                                                <input type="number" name="league_group" class="select-groups" value="0" min="0" max="<?= htmlspecialchars($count_groups["count_groups"]) ?>">
                                                <button id="submit-group">OK</button>
                                                </span>
                                                </td>
                                            <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                                                <td class="undefined"><?= $unclassified_table_nr .". ". htmlspecialchars($one_player["team_name"]) ?>
                                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["team_country"]) ?>.png" alt="">
                                                <span class="choosed-group-OK">
                                                <input type="hidden" name="player_in_league_id" value="<?= htmlspecialchars($one_player["team_in_league_id"]) ?>">
                                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($one_player["league_id"]) ?>">
                                                <input type="number" name="league_group" class="select-groups" value="0" min="0" max="<?= htmlspecialchars($count_groups["count_groups"]) ?>">
                                                <button id="submit-group">OK</button>
                                                </span>
                                                </td>
                                            <?php endif ?>

                                            </form>


                                        </tr>
                                <?php $unclassified_table_nr++; ?>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <tbody>
                        </table>
                    </div>
                    
                    <form action="./after-league-matches.php" method="post">
                        <input type="hidden" name="league_id" value="<?php echo $league_id; ?>">
                        <input id="begin-league" type="submit" value="Zahájiť ligu">
                    </form>
                        
                <?php endif ?>
                </div>
                <!-- ADVANCED SETTINGS TO CREATE LEAGUE ACCORDING GROUPS -start -->
            <?php endif; ?>

        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
    <!-- <script src="../js/league-matches.js"></script> -->
</body>
</html>
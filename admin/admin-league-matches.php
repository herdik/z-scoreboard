<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";
require "../classes/LeaguePlayer.php";



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

if (isset($_GET["league_id"]) and is_numeric($_GET["league_id"])){
    $league_infos = League::getLeague($connection, $_GET["league_id"]);
    $league_id = $league_infos["league_id"];
    $active_league = $league_infos["active_league"];
    $count_groups = LeagueSettings::getLeagueSettings($connection, $league_id, "count_groups");
    $players_without_group = LeaguePlayer::getPlayerGroupInLeague($connection, $league_id);
    $players_in_group = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, false);
} else {
    $league_infos = null;
    $registered_players = null;
    $count_groups = null;
    $players_without_group = null;
    $players_in_group = null;
    $active_league = false;
}

$group_nr = 0;
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

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

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
                    <!-- <div class="leagueHeading">
                        <h1>Názov Ligy
                            <span class="left-icon"></span>
                            <span class="right-icon"></span>
                        </h1> 
                    </div> -->

                    <!-- Liga Rozpis -->
                    <div class="league-matches show-league-matches">

                        <div class="leagueRound">
                            <h1>1.kolo</h1>

                            <div class="matchInformation">
                                
                                <div class="tableNr fisnishedLeagueMatch">
                                    <h3 style="color: rgb(255, 255, 255);">X</h3>
                                </div>

                                <div class="general-match fisnishedLeagueMatch">
                                    <span class="pl1-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Juraj Herda
                                    </span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Ukončiť</button>
                                    </div>

                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">Voľno</span>
                                </div>

                            </div>

                            <div class="matchInformation">

                                <div class="tableNr">
                                    <h3>-</h3>
                                </div>
                                    
                                <div class="general-match">
                                    <span class="pl1-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Janka Herda
                                    </span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Zapnúť</button>
                                    </div>
                                    
                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Zdenko dsf
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="leagueRound">
                            <h1>2.kolo</h1>
                            
                            <div class="matchInformation">

                                <div class="tableNr">
                                    <h3>-</h3>
                                </div>

                                <div class="general-match">
                                    <span class="pl1-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Juraj Herda
                                    </span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Zapnúť</button>
                                    </div>

                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Zdenko dsf
                                    </span>
                                </div>
                            </div>
                            
                            <div class="matchInformation">

                                <div class="tableNr fisnishedLeagueMatch">
                                    <h3 style="color: rgb(255, 255, 255);">X</h3>
                                </div>

                                <div class="general-match fisnishedLeagueMatch">
                                    <span class="pl1-span">Voľno</span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Ukončiť</button>
                                    </div>

                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Janka Herda
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="leagueRound">
                            <h1>3.kolo</h1>
                            
                            <div class="matchInformation">

                                <div class="tableNr">
                                    <h3>-</h3>
                                </div>

                                <div class="general-match">
                                    <span class="pl1-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Juraj Herda
                                    </span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Zapnúť</button>
                                    </div>

                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Janka Herda
                                    </span>
                                </div>
                            </div>

                            <div class="matchInformation">

                                <div class="tableNr fisnishedLeagueMatch">
                                    <h3 style="color: rgb(255, 255, 255);">X</h3>
                                </div>

                                <div class="general-match fisnishedLeagueMatch">
                                    <span class="pl1-span">
                                        <img src="img/flags/Slovensko.png" alt=""> Zdenko dsf
                                    </span>
                                    <label class="pl1-label">0</label>

                                    <div class="btnAndGame">
                                        <img src="img/eight-ball.png" alt="eight-ball">
                                        <button>Ukončiť</button>
                                    </div>

                                    <label class="pl2-label">0</label>
                                    <span class="pl2-span">Voľno</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                </div>

            <!-- Structure for CREATING LEAGUE - STEP BY STEP TO CREATE LEAGUE MATCHES && Structure for *NOT* active league -->
            <?php else: ?>
                
                <!-- BASIC SETTINGS TO CREATE LEAGUE - start -->
                <?php if (($players_without_group != 0) || (count($players_in_group) === 0)): ?>
                <div class="main-container-settings">

                    <!-- No players are registered to current league -->
                    <?php if (count($players_in_group) === 0): ?>
                        <h1>Nedostatočný počet prihlásených hráčov</h1>
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
                                <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?>
                                <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["country"]) ?>.png" alt="">
                                <span class="player-club"><?= htmlspecialchars($one_player["player_club"]) ?></span>
                                <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a></td>
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
                                        <td class="player-table"><?= $table_nr .". ". htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?>
                                        <img class="country-flag" src="../img/countries/<?= htmlspecialchars($one_player["country"]) ?>.png" alt="">
                                        <span class="player-club"><?= htmlspecialchars($one_player["player_club"]) ?></span>
                                        <a class="player-tableX" href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a></td>
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
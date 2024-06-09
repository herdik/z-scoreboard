<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";
require "../classes/LeagueMatch.php";
// require "../classes/LeaguePlayer.php";



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

    if (isset($_GET["league_id"]) and is_numeric($_GET["league_group"])){
        $league_infos = League::getLeague($connection, $_GET["league_id"]);
        $league_id = $league_infos["league_id"];
        $league_name = $league_infos["league_name"];
        $league_group = $_GET["league_group"];
        $active_league = $league_infos["active_league"];
        $count_groups = LeagueSettings::getLeagueSettings($connection, $league_id, "count_groups");
        $league_matches = LeagueMatch::getAllLeagueMatches($connection, $league_id, $league_group);
    } else {
        $league_infos = null;
        $league_id = null;
        $league_name = null;
        $league_group = null;
        $active_league = false;
        $count_groups = null;
        $league_matches = null;
    }
} else {
    echo "Nepovolený prístup";
}

// var_dump(LeagueMatch::getAllLeagueMatches($connection, $league_id, $league_group));
// var_dump($league_name);
// var_dump(count($league_matches));

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

            <div class="league-match-container">
                <!-- OVERVIEW WHEN ONE GROUP IN CURRENT LEAGUE AND SHOW ALL LEAGUE MATCHES-->

                <!-- Názov heading Liga -->
                <div class="leagueHeading">
                    <h1><?= htmlspecialchars($league_name) ?>
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
                            
                            <div class="tableNr">
                                <h3 style= "color:white">-</h3>
                            </div>

                            <div class="general-match">
                                
                                <!-- print profil image for player1 -->
                                <?php if ($league_match["player1_image"] === "no-photo-player"): ?>
                                    <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
                                <?php else: ?>
                                    <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_1"]) . "/" . htmlspecialchars($league_match["player1_image"]) ?> alt="">
                                <?php endif ?> 

                                <span class="pl1-span">

                                        <!-- print coutry flag for player1 -->
                                    <?php if ($league_match["player1_country"] === "none"): ?>
                                        <img src="../img/sbiz.png" alt=""> 
                                    <?php else: ?>
                                        <img src="../img/countries/<?= htmlspecialchars($league_match["player1_country"]) ?>.png" alt="">
                                    <?php endif ?>
                                        <!-- Player name -->
                                        <?= htmlspecialchars($league_match["player1_firstname"]) . " " . htmlspecialchars($league_match["player1_second_name"]) ?>

                                        <!-- club info -->
                                        <div class="main-info-player">    
                                            <?= htmlspecialchars($league_match["player1_club"]) ?>
                                        </div> 

                                    
                                    

                                </span>
                                <label class="pl1-label"><?= htmlspecialchars($league_match["score_1"]) ?></label>

                                <div class="btnAndGame">
                                    <img src=<?="../img/" . htmlspecialchars($league_match["choosed_game"]). "-ball.png" ?> alt=<?= htmlspecialchars($league_match["choosed_game"]). "-ball" ?>>
                                    <button>Zapnúť</button>
                                </div>

                                <label class="pl2-label"><?= htmlspecialchars($league_match["score_2"]) ?></label>
                                <span class="pl2-span">

                                    <!-- print coutry flag for player2 -->
                                    <?php if ($league_match["player2_country"] === "none"): ?>
                                        <img src="../img/sbiz.png" alt=""> 
                                    <?php else: ?>
                                        <img src="../img/countries/<?= htmlspecialchars($league_match["player2_country"]) ?>.png" alt="">
                                    <?php endif ?>  
                                        <!-- Player name -->      
                                        <?= htmlspecialchars($league_match["player2_firstname"]) . " " . htmlspecialchars($league_match["player2_second_name"]) ?>

                                        <!-- club info -->
                                        <div class="main-info-player">    
                                            <?= htmlspecialchars($league_match["player2_club"]) ?>
                                        </div> 
                                </span>

                                <!-- print profil image for player -->
                                <?php if ($league_match["player2_image"] === "no-photo-player"): ?>
                                    <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
                                <?php else: ?>
                                    <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_2"]) . "/" . htmlspecialchars($league_match["player2_image"]) ?> alt="">
                                <?php endif ?>
                            </div>

                        </div>

                    <?php if (count($league_matches) === $counter): ?>    
                    </div>
                    <?php endif ?>

                    <?php endforeach ?>
                </div>

            </div>

        </section>          
    </main>

<?php require "../assets/footer.php" ?>
<script src="../js/header.js"></script>
</body>
</html>
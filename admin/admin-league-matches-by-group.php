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
        $league_matches = LeagueMatch::getAllLeagueMatches($connection, $league_id, $league_group, $league_infos["playing_format"]);
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

                            <?php if ($league_infos["playing_format"] === "single"): ?>
                                <?php require "../assets/league_match_single.php" ?> 

                            <?php elseif ($league_infos["playing_format"] === "doubles"): ?>
                                <?php require "../assets/league_match_doubles.php" ?>                                   
                            <?php endif ?>


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
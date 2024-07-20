<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueTable.php";



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

    if (isset($_GET["league_id"]) and is_numeric($_GET["league_id"])){
        $league_id = $_GET["league_id"];
        $league_group = $_GET["league_group"];
        $league_infos = League::getLeague($connection, $_GET["league_id"]);

        $results_table = LeagueTable::getAllFromLeagueTable($connection, $league_id, $league_group, $league_infos["playing_format"]);
    } else {
        $league_id = null;
        $league_group = null;
        $league_infos = null;

        $results_table = null;
    }

} else {
    echo "Nepovolený prístup";
}
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
    <link rel="stylesheet" href="../css/results.css">
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
                <li><a href="./results.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>&league_group=1">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

             <!-- Tabuľka výsledkov -->

        <!-- <div class="result-container"> -->
            <h1>Tabuľka výsledkov</h1>

            <div class="show-table-results">

                <!-- Zoznam registrovaných hráčov -->
                <table class="results-table ">
                    <thead>
                        <tr>
                            <th>Poradie</th>
                            <th class="player-name-table">Hráč</th>
                            <th>Zápasy</th>
                            <th>Výhry</th>
                            <th>Prehry</th>
                            <th>Skóre</th>
                            <th>Rozdiel</th>
                            <th>Body</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i=0; $i<count($results_table);$i++): ?>
                        <tr>
                            <td><?= $i + 1 ."." ?></td>
                            <td class="player-name-table"><img src="../img/countries/<?= htmlspecialchars($results_table[$i]["player_country"]) ?>.png" alt="<?= htmlspecialchars($results_table[$i]["player_country"]) ?>"><?= htmlspecialchars($results_table[$i]["player_firstname"]) . " " . htmlspecialchars($results_table[$i]["player_second_name"])?></td>
                            <td><?= htmlspecialchars($results_table[$i]["played_matches"])?></td>
                            <td><?= htmlspecialchars($results_table[$i]["winnings_matches"]) ?></td>
                            <td><?= htmlspecialchars($results_table[$i]["lost_matches"]) ?></td>
                            <td><?= htmlspecialchars($results_table[$i]["score_game_win"]) . ":" . htmlspecialchars($results_table[$i]["score_game_loss"])?></td>
                            <td><?= htmlspecialchars($results_table[$i]["difference"]) ?></td>
                            <td><?= htmlspecialchars($results_table[$i]["points"]) ?></td>
                        </tr>
                    <?php endfor ?>
                       
                    </tbody>
                </table>

            </div>
            
            

        <!-- </div> -->                 
        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
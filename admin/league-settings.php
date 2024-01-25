<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";



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
    $league_settings = LeagueSettings::getLeagueSettings($connection, $_GET["league_id"]);
    if (!$league_settings){
        $league_settings["revenge"] = "0";
        $league_settings["race_to"] = "1";
        $league_settings["count_tables"] = "1";
        $league_settings["count_groups"] = "1";
    }
    
} else {
    $league_infos = null;
    $league_settings = null;
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
    <link rel="stylesheet" href="../css/league-settings.css">
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
                <li><a href="./league-settings.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Nastavenia</a></li>
                <li><a href="#">Ligové zápasy</a></li>
                <li><a href="#">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

        <div class="system-container">

            <h1>Nastavenia ligy</h1>

            <!-- Formulár pre registáciu hráča -->
            <form id="league-settings" action="after-league-settings.php" method="POST">

                <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>


                <input type="checkbox" id="revenge" name="revenge" value="<?= htmlspecialchars($league_settings["revenge"]) ?>">
                <label for="revenge">Odvety</label><br>

                
                <label for="raceTo">Hrať do</label>
                <input type="number" id="raceTo" min="1" value="<?= htmlspecialchars($league_settings["race_to"]) ?>" step="1" name="race_to"><br>

                <!-- Počet stolov -->
                <label for="count-tables">Počet stolov</label>
                <input type="number" id="count-tables" min="1" value="<?= htmlspecialchars($league_settings["count_tables"]) ?>" step="1" name="count_tables"><br>

                <!-- Počet skupín -->
                <label for="count-groups">Počet skupín</label>
                <input type="number" id="count-groups" min="1" value="<?= htmlspecialchars($league_settings["count_groups"]) ?>" step="1" name="count_groups"><br>
                
                <!-- odosielacie tlačítko pre odolsanie údajov od užívateľa pre nastavenia hry -->
                <input type="submit" value="Potvrdiť" name="submitForm">

            </form>

        </div>   

        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
    <script src="../js/league-settings.js"></script>
</body>
</html>
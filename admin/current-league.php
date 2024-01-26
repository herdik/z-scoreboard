<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";



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
} else {
    $league_infos = null;
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
    <link rel="stylesheet" href="../css/current-league.css">
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
                <?php if ($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]): ?>
                    <li><a href="././league-settings.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Nastavenia</a></li>
                <?php endif; ?>
                <li><a href="#">Ligové zápasy</a></li>
                <li><a href="#">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

            <div class="category">
                <h2>Kategória</h2>
                <p><?= htmlspecialchars($league_infos["category"]) ?></p>
            </div>

            <div class="main-info-content">
                <img id="black-manila" src="../img/black-logo-manila.png" alt="">

                <h1><?= htmlspecialchars($league_infos["league_name"]) ?></h1>
                <p><?= htmlspecialchars(date("d-m-Y", strtotime($league_infos["date_of_event"]))) ?></p>
                <img id="discipline" src="../img/<?= htmlspecialchars($league_infos["discipline"]) ?>-ball.png" alt="">

                <h3>Hrací formát</h3>
                
                <?php if (htmlspecialchars($league_infos["playing_format"]) === "single"): ?>
                    <p>Jednotlivci</p>
                    <p><i class="fa-solid fa-person"></i></p>
                <?php elseif (htmlspecialchars($league_infos["playing_format"]) === "doubles"): ?>
                    <p>Dvojice</p>
                    <p><i class="fa-solid fa-person"></i><i class="fa-solid fa-person"></i></p>
                <?php else: ?> 
                    <p>Družstvá</p>
                    <p><i class="fa-solid fa-people-group"></i></p>
                <?php endif; ?>  
                
                <h2>Sezóna</h2>
                <p><?= htmlspecialchars($league_infos["season"]) ?></p>
            
                <img id="sbiz" src="../img/sbiz.png" alt="">
            </div>

            <div class="category">
                <h2>Miesto konania</h2>
                <p><?= htmlspecialchars($league_infos["venue"]) ?></p>
            </div>

        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
<?php

    require "../classes/Database.php";
    require "../classes/Player.php";

    // verifying by session if visitor have access to this website
    require "../classes/Authorization.php";
    // get session
    session_start();
    // authorization for visitor - if has access to website 
    if (!Auth::isLoggedIn()){
        die ("nepovolený prístup");
    }

    // connection to Database
    $database = new Database();
    $connection = $database->connectionDB();



    // $_GET["player_Id"] -> získam id z url adresy čo je za za php?
    // isset a is_numeric je kvôli bezpečnosti aby niekto nezadal napr DROP TABLE
    // isset teda či premenná/hodnota existuje a nie je NULL, teda či je vôbec nastavená
    // is_numeric či je zadaná hodnota číslo

    if (isset($_GET["player_Id"]) and is_numeric($_GET["player_Id"])){
        $player_infos = Player::getUser($connection, $_GET["player_Id"]);
    } else {
        $player_infos = null;
    }

?>

<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil hráča</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/player-info.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="player-heading">

            <h1>Užívateľský profil hráča</h1>

        </section>

        <section class="player-main-info">
                <?php if ($player_infos === NULL): ?>
                    <p>Hráč nebol nájdený<p>
                <?php else: ?>
                    <div class="one-player-box">
                    <?php if ($_SESSION["role"] === "admin" || $_SESSION["role"] === "organizer"): ?>
                        <h1>Email: <?= htmlspecialchars($player_infos["user_email"]) ?></h1>
                    <?php endif ?>   
                        <h1><?= htmlspecialchars($player_infos["first_name"]). " " .htmlspecialchars($player_infos["second_name"]) ?></h1>
                        <h3>Klub: <?= htmlspecialchars($player_infos["player_club"]) ?></h3>

                        <div class="cues">
                            <p>Hracie tágo: <?= htmlspecialchars($player_infos["player_cue"]) ?></p>
                            <p>Rozbíjacie tágo: <?= htmlspecialchars($player_infos["player_break_cue"]) ?></p>
                            <p>Názov školy: <?= htmlspecialchars($player_infos["player_jump_cue"]) ?></p>
                        </div>
                    </div>
 
                <?php endif ?>
        </section>
           
        <?php if ($_SESSION["role"] === "admin"): ?>
        <section class="buttons">
            <a href="./edit-player.php?player_Id=<?= htmlspecialchars($player_infos["player_Id"]) ?>" class="edit-btn btns">Upraviť</a>
            <a href="./delete-player.php?player_Id=<?= htmlspecialchars($player_infos["player_Id"]) ?>" class="delete-btn btns">Vymazať</a>
        </section>
        <?php endif ?>
    </main>
    
    <?php require "../assets/footer.php" ?>
    <script src="./js/header.js"></script>                
</body>
</html>
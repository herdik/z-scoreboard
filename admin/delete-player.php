<?php

    require "../classes/Database.php";
    require "../classes/Player.php";
    require "../classes/Url.php";


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

    if ( isset($_GET["player_Id"]) and is_numeric($_GET["player_Id"])){

    } else {
        die("Hráč nie je nájdený!!!");
    }

    $player_infos = Player::getUser($connection, $_GET["player_Id"]);
    $player_name = $player_infos["first_name"] . " " . $player_infos["second_name"];

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        if (Player::deletePlayer($connection, $_GET["player_Id"])) {
            Url::redirectUrl("/z-scoreboard/admin/admin-players-list.php");
        }
    }

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vymazanie hráča</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/delete-player.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="alert-triangel">
            <h1>!</h1>
        </section>

        <section class="registration-form">
            <h3>Naozaj chcete VYMAZAŤ hráča?</h3>
            <h1><?= $player_name ?></h1>
        </section>

        <section class="delete-form">
            <form action="" method="POST">

            
            <a href="./player-profil.php?player_Id=<?= htmlspecialchars($player_infos['player_Id']) ?>" class="cancel-btn btns">Zrušiť</a>
            <input class="delete-btn btns" type="submit" value="Vymazať">

            </form>
        </section>
        
        
    </main>
    
    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>




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

// database connection
$database = new Database();
$connection = $database->connectionDB();

$players = Player::getAllPlayers($connection, "player_Id, first_name, second_name, player_club");

?>


<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoznam hráčov - administrácia</title>

    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/players-list.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>

</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="main-heading">

            <h1>Zoznam hráčov</h1>

        </section>
        

        <section class="all-registered-players">
            <!-- Zoznam registrovaných hráčov -->
            <ol class="registered-players-list">
            <?php foreach($players as $one_player): ?>

                <li>
                    <img src="img/Slovensko.png" alt="Slovensko">
                    <div class="player-informations">
                        <h3><?php echo htmlspecialchars($one_player["first_name"]). " ". htmlspecialchars($one_player["second_name"]) ?></h3>
                        <p><?php echo htmlspecialchars($one_player["player_club"]) ?></p>
                    </div>
                    <a class="info-btn" href="player-profil.php?player_Id=<?= htmlspecialchars($one_player['player_Id']) ?>">Informácie <br>o hráčovi</a>
                </li>
                
            <?php endforeach ?>
            </ol>

        </section>
        

    </main>
    
    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>            
</body>
</html>



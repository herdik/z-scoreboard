<?php

require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/Url.php";


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

$leagues = League::getAllLeagues($connection);

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoznam súťaží - administrácia</title>

    <link rel="icon" type="image/x-icon" href="./img/favicon.ico">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tektur&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/competitions.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="competitions-create-btns">

            <a class="create-competition-btn" href="create-league.php">Vytvoriť ligu</a>
            <a class="create-competition-btn" href="">Vytvoriť turnaj</a>
            

        </section>

        <section class="main-heading">
            
            <h1>Zoznam súťaží</h1>

        </section>
        

        <section class="all-registered-competitions">
            <!-- Zoznam registrovaných súťaží -->
            <div class="result-container">
                
                <div class="league-table">

                    <table class="list-of-league">
                        <thead>
                            <tr>
                                <th>Formát</th>
                                <th>Názov</th>
                                <th style="display:none;">Dátum</th>
                                <th>Kategória</th>
                                <th>Hra</th>
                                <th>Sezóna</th>
                                <th>Miesto</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($leagues as $one_league): ?>

                            
                                <tr onclick='window.location="./current-league.php?league_id=<?= htmlspecialchars($one_league["league_id"]) ?>";'>

                                <?php if (htmlspecialchars($one_league["playing_format"]) === "single"): ?>
                                    <td><i class="fa-solid fa-person"></i></td>
                                <?php elseif (htmlspecialchars($one_league["playing_format"]) === "doubles"): ?>
                                    <td><i class="fa-solid fa-person"></i><i class="fa-solid fa-person"></i></td>
                                <?php else: ?> 
                                    <td><i class="fa-solid fa-people-group"></i></td>
                                <?php endif; ?>   
                                
                                <td><?= htmlspecialchars($one_league["league_name"]) ?></td>
                                <td><?= htmlspecialchars($one_league["category"]) ?></td>
                                <td><img src="../img/<?= $one_league["discipline"] ?>-ball.png" alt=""></td>
                                <td><?= htmlspecialchars($one_league["season"]) ?></td>
                                <td><?= htmlspecialchars($one_league["venue"]) ?></td>
                                <td style="display:none;"><?= htmlspecialchars($one_league["type"]) ?></td>
                                
                                </tr>
                            

                        <?php endforeach ?>   
                        </tbody>
                    </table>
                </div>
            </div>
                
            

        </section>
        

    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
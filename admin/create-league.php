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

// database connection
$database = new Database();
$connection = $database->connectionDB();

?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vytvorenie ligy</title>

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

        <section class="main-heading">
            
            <h1>Vytvoriť ligu</h1>

        </section>

        <section class="main-league-settings">
            <!-- Hlavný názov -->
        <h1>BK MANILA " Z-scoreboard "</h1>


        <!-- logo manila -->
        <header>
            <img src="img/logo-white-manila.png" alt="">
        </header>


        <!-- Formulár pre zadanie názvu ligy -->
        <div class="zero-container">
            
            <form id="leagueName-form">
                <input type="text" placeholder="Názov ligy" name="leagueName">
                <input type="submit" value="Potvrdiť" name="submitLeagueName">
            </form>
            <h1 class="heading-name-league">Názov ligy
                <span class="left-icon"></span>
                <span class="right-icon"></span>
            </h1>
        </div>

        <!-- Nastavenia pre vytvorenie ligy + system-container -->

        <div class="system-container">

            <h1>Nastavenia ligy</h1>

            <!-- Formulár pre registáciu hráča -->
            <form id="league-settings">

                <input type="checkbox" id="revenge" name="checkbox">
                <label for="revenge">Odvety</label>
                <!-- výber krajiny z options -->
                <select id="match-settings" name="matchSettings">
                    <option value="single">Jednotlivci</option>
                    <option value="doubles">Dvojice</option>
                    <option value="teams">Teamy</option>
                </select><br>
                <!-- Hrať do -->

                <label for="raceTo">Hrať do</label>
                <input type="number" id="raceTo" min="0" value="1" step="1" name="raceTo"><br>

                <!-- Počet stolov -->
                <label for="count-tables">Počet stolov</label>
                <input type="number" id="count-tables" min="0" value="1" step="1" name="countTables"><br>
                
                <!-- výber typu hry -->
                <div class="game-menu">

                    <div class="game-title">
                        Typ hry
                    </div>

                    <div class="game-option-list">
                        <ul class="defaultOption">
                            <li>
                                <div class="gameOption default">
                                    <div class="ballImage"><img src="img/eight-ball.png" alt="eight-ball"></div>
                                </div>
                            </li>
                        </ul>

                        <ul class="chooseGame">
                            <li>
                                <div class="gameOption">
                                    <div class="ballImage"><img src="img/eight-ball.png" alt="eight-ball"></div>
                                </div>
                            </li>
                            <li>
                                <div class="gameOption">
                                    <div class="ballImage"><img src="img/nine-ball.png" alt="nine-ball"></div>
                                </div>
                            </li>
                            <li>
                                <div class="gameOption">
                                    <div class="ballImage"><img src="img/ten-ball.png" alt="ten-ball"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- odosielacie tlačítko pre odolsanie údajov od užívateľa pre nastavenia hry -->
                <input type="submit" value="Potvrdiť" name="submitForm">

            </form>
                </section>
        </div>   

    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
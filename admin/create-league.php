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


// $testDate = '12.01.21'; 

// $date = DateTime::createFromFormat('d.m.y', $testDate);
// echo $date->format('Y-m-d'); 

// *echo date("Y/m/d", strtotime($testDate));


$league_name = "";
$date_of_event = "";
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
    <link rel="stylesheet" href="../css/create-league.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../query/header-query.css">

    <script src="https://kit.fontawesome.com/ed8b583ef3.js" crossorigin="anonymous"></script>
</head>
<body>

    <?php require "../assets/admin-organizer-header.php" ?>

    <main>

        <section class="main-heading">
            
            <h1>Vytvoriť ligu</h1>
            <h1 class="app-name"></h1>

            <!-- logo manila -->
            <div class="moving-logo">
                <img src="../img/logo-white-manila.png" alt="">
            </div>

        </section>

        <section class="main-league-settings">
           
            

            <form id="basic-settings-form" action="after-create-league.php" method="post">

                <input type="text" name="league_name" id="league_name" placeholder="Názov Ligy" value="<?= htmlspecialchars($league_name) ?>" required>
                <input type="date" name="date_of_event" id="date_of_event" value="<?= htmlspecialchars($date_of_event) ?>" required>

                <div class="basic-settings">
                    <div class="game-settings">
                        
                        <div class="container">
                            <input type="radio" name="discipline" id="8-ball" value="<?= htmlspecialchars("8") ?>" required>
                            <label for="8-ball"><img src="../img/eight-ball.png" alt=""></label>
                        </div>
                        
                        <div class="container">
                            <input type="radio" name="discipline" id="9-ball" value="<?= htmlspecialchars("9") ?>" required>
                            <label for="9-ball"><img src="../img/nine-ball.png" alt=""></label>
                        </div>

                        <div class="container">
                            <input type="radio" name="discipline" id="10-ball" value="<?= htmlspecialchars("10") ?>" required>
                            <label for="10-ball"><img src="../img/ten-ball.png" alt=""></label>
                        </div>

                        <div class="container">
                            <input type="radio" name="discipline" id="14-ball" value="<?= htmlspecialchars("14") ?>" required>
                            <label for="14-ball"><img src="../img/straight-pool.png" alt=""></label>
                        </div>

                    </div>

                    <div class="game-settings">

                        <div class="container">
                            <input type="radio" name="playing_format" id="single" value="<?= htmlspecialchars("single") ?>" required>
                            <label for="single"> Jednotlivci <i class="fa-solid fa-person"></i></label>

                        </div>
                        
                        <div class="container">
                            <input type="radio" name="playing_format" id="doubles" value="<?= htmlspecialchars("doubles") ?>" required>
                            <label for="doubles">Dvojice <i class="fa-solid fa-person"></i><i class="fa-solid fa-person"></i></label>
                            
                        </div>    

                        <div class="container">
                            <input type="radio" name="playing_format" id="teams" value="<?= htmlspecialchars("teams") ?>" required>
                            <label for="teams">Družstvá<i class="fa-solid fa-people-group"></i></label>
                            
                        </div>
                    </div>

                </div>

                <h1>Miesto konania</h1>
                <div class="venue-settings">

                    <div class="container">
                        <input type="radio" name="venue" id="bkmanila" value="<?= htmlspecialchars("BK MANILA") ?>" required>
                        <label for="bkmanila">BK MANILA</label>

                    </div>
                    
                    <div class="container">
                        <input type="radio" name="venue" id="sbiz" value="<?= htmlspecialchars("SBIZ") ?>" required>
                        <label for="sbiz">SBIZ</label>
                        
                    </div>    

                    <div class="container">
                        <input type="radio" name="venue" id="point" value="<?= htmlspecialchars("POINT") ?>" required>
                        <label for="point">POINT</label>
                        
                    </div>
                    <div class="container">
                        <input type="radio" name="venue" id="lavos" value="<?= htmlspecialchars("LAVOS") ?>" required>
                        <label for="lavos">LAVOS</label>
                        
                    </div>
                    <div class="container">
                        <input type="radio" name="venue" id="arena" value="<?= htmlspecialchars("ARÉNA") ?>" required>
                        <label for="arena">ARÉNA</label>
                        
                    </div>
                    <div class="container">
                        <input type="radio" name="venue" id="pardubice" value="<?= htmlspecialchars("PARDUBICE") ?>" required>
                        <label for="pardubice">PARDUBICE</label>
                        
                    </div>
                </div>

                <label for="season" id="season">Sezóna</label>
                <input type="number" name="season" id="season-input" min="2020" max="2099" value="<?= htmlspecialchars(date("Y")) ?>" required>
                
                <input type="submit" id="submit-btn" value="Potvrdiť">
                

            </form>

        </section>
        
        
            
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
    <script src="../js/repeating-Z-scoreboard.js"></script>
</body>
</html>
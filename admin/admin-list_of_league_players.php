<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeaguePlayer.php";



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

// button registrovať - Registration
$reg_button = "Registrovať";

if (isset($_GET["league_id"]) and is_numeric($_GET["league_id"])){
    $league_infos = League::getLeague($connection, $_GET["league_id"]);
    $registered_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_infos["league_id"]);

    foreach($registered_players as $one_reg_player){
        if($_SESSION["logged_in_user_id"] === $one_reg_player["player_Id"]){
            $reg_button = "Odregistrovať";
            break;
        } else {
            $reg_button = "Registrovať";
        }
    }     
} else {
    $league_infos = null;
    $registered_players = null;
}

$total_players = count($registered_players);

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
    <link rel="stylesheet" href="../css/list-of-league-players.css">
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
                <li><a href="#">Nastavenia</a></li>
                <li><a href="#">Ligové zápasy</a></li>
                <li><a href="#">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

            <div class="main-info">
                <div class="basic-info">
                    <h1><?= htmlspecialchars($league_infos["league_name"]) ?></h1>
                    <p><?= htmlspecialchars(date("d-m-Y", strtotime($league_infos["date_of_event"]))) ?></p>

                    <h1 id="venue">Miesto konania</h1>
                    <p><?= htmlspecialchars($league_infos["venue"]) ?></p>

                    <h5 id="total-reg-players">Počet registrovaných hráčov</h5>
                    <p><?= htmlspecialchars($total_players) ?></p>
                </div>
                
                <div class="logos">
                    <img id="black-manila" src="../img/black-logo-manila.png" alt="">   
                    <img id="sbiz" src="../img/sbiz.png" alt="">  
                </div>
                
                    
                <form id="reg-league-form" action="./create-league-player.php" method="POST">
                    
                    <div class="form-content">
                        <h1>Registrácia</h1>
                        <div class="form-info">

                            <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                            <input type="hidden" name="player_Id" value="<?= htmlspecialchars($user_info["player_Id"]) ?>" readonly>

                            <div class="player-names">
                                <label for="first_name">Meno:</label>
                                <!-- $user_info get from admin-organizer-header -->
                                <input type="text" name="first_name"  value="<?= htmlspecialchars($user_info["first_name"]) ?>" readonly>
                            </div>

                            <div class="player-names">
                                <label for="second_name">Priezvisko:</label>
                                <input type="text" name="second_name"  value="<?= htmlspecialchars($user_info["second_name"]) ?>" readonly>
                            </div>

                            
                            <div class="sumbit-btn">
                                <input type="submit" value="<?= htmlspecialchars($reg_button) ?>">
                            </div>

                        </div>

                    </div>
                   
                </form>

            </div>
            

            <div class="registered-players">
                <h2>Registrovaní hráči</h2>
                

                <div class="players">

                    <?php foreach($registered_players as $reg_player): ?>
                        <article class="player-profil">
                            <div class="picture-part">
                                <div class="flag-part" style="
                                    background: url(../img/countries/<?= htmlspecialchars($reg_player["country"]) ?>.png);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                                </div>
                            </div>
                            <h6 class="profil-name"><?php echo htmlspecialchars($reg_player["first_name"]). " ". htmlspecialchars($reg_player["second_name"]) ?></h6>
                            <a href="./player-profil.php?player_Id=<?= htmlspecialchars($reg_player["player_Id"]) ?>">Informácie</a>
                        </article>
                    <?php endforeach ?>

                </div>
        
                
                
            </div>   
            

        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
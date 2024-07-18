<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeaguePlayer.php";
require "../classes/LeaguePlayerDoubles.php";
require "../classes/LeagueTeam.php";



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
    $league_id = $league_infos["league_id"];


    if ($league_infos["playing_format"] === "single") {
        $all_players = LeaguePlayer::getAllLeaguePlayersNotRegistered($connection, $league_id);
        $registered_players = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, true);
        $total_players = count($registered_players);
        

        foreach($registered_players as $one_reg_player){
            if($_SESSION["logged_in_user_id"] === $one_reg_player["player_Id"]){
                $reg_button = "Odregistrovať";
                break;
            } else {
                $reg_button = "Registrovať";
            }
        } 
    } elseif ($league_infos["playing_format"] === "doubles"){
        $all_players = LeaguePlayerDoubles::getAllLeaguePlayersNotRegistered($connection, $league_id);
        $registered_doubles = LeaguePlayerDoubles::getAllLeagueDoubles($connection, $league_id, true);
        $total_players = count($registered_doubles);

        foreach($registered_doubles as $one_reg_doubles){
            if(($_SESSION["logged_in_user_id"] === $one_reg_doubles["player_Id_doubles_1"]) || ($_SESSION["logged_in_user_id"] === $one_reg_doubles["player_Id_doubles_2"])){
                $reg_button = "Odregistrovať";
                break;
            } else {
                $reg_button = "Registrovať";
            }
        }
    } elseif ($league_infos["playing_format"] === "teams") {
        $all_players = LeagueTeam::getAllLeagueTeamsNotRegistered($connection, $league_id);
        $registered_players = LeagueTeam::getAllLeagueTeams($connection, $league_id, true);
        $total_players = count($registered_players);
        
        $loged_in_player = Player::getPlayer($connection, $_SESSION["logged_in_user_id"]);

        foreach($registered_players as $one_reg_player){
            if($loged_in_player["player_club_id"] === $one_reg_player["team_id"]){
                $reg_button = "Odregistrovať";
                break;
            } else {
                $reg_button = "Registrovať";
            }
        } 
    }

        
} else {
    $league_infos = null;
    $registered_players = null;
    $registered_doubles = null;
}

// foreach($registered_players as $one_reg_player){
//     var_dump($one_reg_player["league_id"]);
//     var_dump($one_reg_player["league_group"]);
//     var_dump($one_reg_player["player_Id"]);
// }
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
                <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <li><a href="./league-settings.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Nastavenia</a></li>
                <?php endif; ?>
                <li><a href="./admin-league-matches.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Ligové zápasy</a></li>
                <li><a href="./results.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

            <div class="main-info">
                <div class="basic-info">
                    <h1><?= htmlspecialchars($league_infos["league_name"]) ?></h1>
                    <p><?= htmlspecialchars(date("d-m-Y", strtotime($league_infos["date_of_event"]))) ?></p>

                    <h1 id="venue">Miesto konania</h1>
                    <p><?= htmlspecialchars($league_infos["venue"]) ?></p>

                <?php if ($league_infos["playing_format"] === "single"): ?> 
                    <h5 id="total-reg-players">Počet registrovaných hráčov</h5>
                <?php elseif ($league_infos["playing_format"] === "doubles"): ?> 
                    <h5 id="total-reg-players">Počet registrovaných dvojíc</h5>
                <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                    <h5 id="total-reg-players">Počet registrovaných družstiev</h5>
                <?php endif ?>
                    <p><?= htmlspecialchars($total_players) ?></p>

                </div>
                
                <div class="logos">
                    <img id="black-manila" src="../img/black-logo-manila.png" alt="">   
                    <img id="sbiz" src="../img/sbiz.png" alt="">  
                </div>
                

                <!-- league players for registration to current league SINGLE -start -->
                <?php if ($league_infos["playing_format"] === "single"): ?>
                    <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <form id="reg-players-in-league-form" action="./create-league-players.php" method="POST">

                        <label for="reg-players">Registrácia hráča/hráčov</label>
            
                        <div class="main-container-select">

                        
                            <div class="select-container single">
                                
                            <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>

                                <?php foreach ($all_players as $one_player): ?>
                                    <div class="player-line">
                                        <input type="checkbox" id="player-<?= $one_player["player_Id"] ?>" name="selected_players_id[]" value="<?= $one_player["player_Id"] ?>">
                                        <label for="player-<?= $one_player["player_Id"] ?>"><?= $one_player["second_name"] ." ". $one_player["first_name"] ?></label>
                                    </div>
                                    
                                <?php endforeach; ?>

                                
                            </div>

                        </div>
                            <h6>Zoznam hráčov</h6>
                        <div class="selected-players-league">
                            
                        </div>

                        <div class="sumbit-btn">
                            <input type="submit" value="Registrovať">
                        </div>
                        
                    </form>  
                    <!-- js script to show all selected player in div list to registration for current league -->
                    <script src="../js/select-options.js"></script>
                    <?php else: ?>
                        
                        <?php if ($reg_button === "Registrovať"): ?>
                        <form id="reg-league-form" action="./create-league-player.php" method="POST">

                        <?php elseif ($reg_button === "Odregistrovať"): ?>
                        <form id="reg-league-form" action="./delete-player-in-league.php" method="POST">

                        <?php endif; ?> 
                        
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

                    <?php endif; ?> 

                    
                <!-- league players for registration to current league SINGLE -finish -->        

                <!-- league players for registration to current league DOUBLES -start -->
                <?php elseif ($league_infos["playing_format"] === "doubles"): ?>

                    <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                        
                    <form id="reg-league-form" action="./create-league-player.php" method="POST">
                        
                        <div class="form-content doubles-organizator">
                            <h1>Registrácia</h1>
                            
                            <article class="reg-form-doubles">

                                <label for="reg-players">Hráč č.1 do dvojice:</label>
                    
                                <div class="main-container-select">

                                
                                    <div class="select-container doubles-partA">
                                        
                                    <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>

                                        <?php foreach ($all_players as $one_player): ?>
                                            <div class="player-line">
                                                <input type="radio" id="player_Id_doubles_1-<?= $one_player["player_Id"] ?>" name="player_Id_doubles_1" value="<?= $one_player["player_Id"] ?>">
                                                <label for="player_Id_doubles_1-<?= $one_player["player_Id"] ?>"><?= $one_player["second_name"] ." ". $one_player["first_name"] ?></label>
                                            </div>
                                            
                                        <?php endforeach; ?>

                                        
                                    </div>

                                </div>
                                    <h6>Hráč č.1 pre dvojicu</h6>
                                <div class="selected-players-league">
                                    
                                </div>
                                
                            </article>

                            <article class="reg-form-doubles">

                                <label for="reg-players">Hráč č.2 do dvojice:</label>
                    
                                <div class="main-container-select">

                                
                                    <div class="select-container doubles-partB">
                                        
                                        <?php foreach ($all_players as $one_player): ?>
                                            <div class="player-line">
                                                <input type="radio" id="player_Id_doubles_2-<?= $one_player["player_Id"] ?>" name="player_Id_doubles_2" value="<?= $one_player["player_Id"] ?>">
                                                <label for="player_Id_doubles_2-<?= $one_player["player_Id"] ?>"><?= $one_player["second_name"] ." ". $one_player["first_name"] ?></label>
                                            </div>
                                            
                                        <?php endforeach; ?>

                                        
                                    </div>

                                </div>
                                    <h6>Hráč č.2 pre dvojicu</h6>
                                <div class="selected-player2-league">
                                    
                                </div>
                                
                            </article>
                        
                        <div class="sumbit-btn">
                            <input type="submit" value="Registrovať">
                        </div>

                        </div>

                           
                    </form>

                    <?php else: ?>
                               
                        <?php if ($reg_button === "Registrovať"): ?>
                    <form id="reg-league-form" action="./create-league-player.php" method="POST">

                        <?php elseif ($reg_button === "Odregistrovať"): ?>
                    <form id="reg-league-form" action="./delete-doubles-in-league.php" method="POST">
                        
                        <?php endif; ?> 
                        
                        <div class="form-content">
                            <h1>Registrácia</h1>
                            <div class="form-info">

                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                                <input type="hidden" name="player_Id_doubles_1" value="<?= htmlspecialchars($user_info["player_Id"]) ?>" readonly>

                                <div class="player-names">
                                    <label for="first_name">Meno:</label>
                                    <!-- $user_info get from admin-organizer-header -->
                                    <input id="f_name_reg" type="text" name="first_name"  value="<?= htmlspecialchars($user_info["first_name"]) ?>" readonly>
                                </div>

                                <div class="player-names">
                                    <label for="second_name">Priezvisko:</label>
                                    <input id="s_name_reg" type="text" name="second_name"  value="<?= htmlspecialchars($user_info["second_name"]) ?>" readonly>
                                </div>


                            </div> 

                            <article class="reg-form-doubles second-player-doubles">

                                <label for="reg-players">Hráč do dvojice:</label>
                    
                                <div class="main-container-select">

                                
                                    <div class="select-container doubles">
                                        
                                    

                                        <?php foreach ($all_players as $one_player): ?>
                                            <div class="player-line">
                                                <input type="radio" id="player_Id_doubles_2-<?= $one_player["player_Id"] ?>" name="player_Id_doubles_2" value="<?= $one_player["player_Id"] ?>">
                                                <label for="player_Id_doubles_2-<?= $one_player["player_Id"] ?>"><?= $one_player["second_name"] ." ". $one_player["first_name"] ?></label>
                                            </div>
                                            
                                        <?php endforeach; ?>

                                        
                                    </div>

                                </div>
                                    <h6>Hráč č.2 pre dvojicu</h6>
                                <div class="selected-players-league">
                                    
                                </div>
                                
                            </article>
                        
                        <div class="sumbit-btn">
                            <input id="doubles-reg-btn" type="submit" value="<?= htmlspecialchars($reg_button) ?>">
                        </div>

                        </div>

                    <script src="../js/hide-option-player2-doubles.js"></script>

                    </form>
                        
                                   
                        
                                           
                    <?php endif; ?>
                    <!-- js script for select only one choice for player 1 or player 2 in doubles -->
                    <script src="../js/select-options-doubles.js"></script>
                <!-- league players for registration to current league DOUBLES -finish -->
            
                <!-- league players for registration to current league TEAMS -start -->
                <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                    <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <form id="reg-players-in-league-form" action="./create-league-players.php" method="POST">

                        <label for="reg-players">Registrácia družstva/družstiev</label>
            
                        <div class="main-container-select">

                        
                            <div class="select-container single">
                                
                            <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>

                                <?php foreach ($all_players as $one_player): ?>
                                    <div class="player-line">
                                        <input type="checkbox" id="player-<?= $one_player["team_id"] ?>" name="selected_teams_id[]" value="<?= $one_player["team_id"] ?>">
                                        <label for="player-<?= $one_player["team_id"] ?>"><?= $one_player["team_name"] ?></label>
                                    </div>
                                    
                                <?php endforeach; ?>

                                
                            </div>

                        </div>
                            <h6>Zoznam hráčov</h6>
                        <div class="selected-players-league">
                            
                        </div>

                        <div class="sumbit-btn">
                            <input type="submit" value="Registrovať">
                        </div>
                        
                    </form>  
                    <!-- js script to show all selected team in div list to registration for current league -->
                    <script src="../js/select-options.js"></script>
                    <?php else: ?>
                        
                        <?php if ($reg_button === "Registrovať"): ?>
                        <form id="reg-league-form" action="./create-league-player.php" method="POST">

                        <?php elseif ($reg_button === "Odregistrovať"): ?>
                        <form id="reg-league-form" action="./delete-team-in-league.php" method="POST">

                        <?php endif; ?> 
                        
                        <div class="form-content">
                            <h1>Registrácia</h1>
                            <div class="form-info">

                                <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                                <input type="hidden" name="team_id" value="<?= htmlspecialchars($user_info["player_club_id"]) ?>" readonly>

                                <div class="player-names">
                                    <label for="team_name">Názov družstva:</label>
                                    <!-- $user_info get from admin-organizer-header -->
                                    <input type="text" name="team_name"  value="<?= htmlspecialchars($user_info["player_club"]) ?>" readonly>
                                </div>

                                <div class="sumbit-btn">
                                    <input type="submit" value="<?= htmlspecialchars($reg_button) ?>">
                                </div>

                            </div>

                        </div>
                    
                    </form>

                    <?php endif; ?> 

                    
                <!-- league players for registration to current league TEAMS -finish -->

                <?php endif; ?>      
                                           
            </div>
            

            <div class="registered-players">

            <?php if ($league_infos["playing_format"] === "single"): ?>
                <h2>Registrovaní hráči</h2>
                

                <div class="players">

                    <?php foreach($registered_players as $reg_player): ?>
                        <article class="player-profil">

                        <?php if (htmlspecialchars($reg_player["player_Image"]) === "no-photo-player"): ?>
                            <div class="picture-part" style="
                                    background: url(../img/<?= htmlspecialchars($reg_player["player_Image"]) ?>.png);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                        <?php else: ?>
                            <div class="picture-part" style="
                                    background: url(../uploads/<?= htmlspecialchars($reg_player["player_Id"]) ?>/<?= htmlspecialchars($reg_player["player_Image"]) ?>);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                        <?php endif; ?>
                    
                                <div class="flag-part" style="
                                    background: url(../img/countries/<?= htmlspecialchars($reg_player["country"]) ?>.png);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                                </div>
                            </div>
                            <h6 class="profil-name"><?php echo htmlspecialchars($reg_player["first_name"]). " ". htmlspecialchars($reg_player["second_name"]) ?></h6>
                            <p class="profil-club"><?php echo htmlspecialchars($reg_player["player_club"]) ?></p>
                            <a class="player-infos" href="./player-profil.php?player_Id=<?= htmlspecialchars($reg_player["player_Id"]) ?>">Informácie</a>

                            <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                                
                                <a class="player-profilX" href="delete-player-in-league.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>&player_Id=<?= htmlspecialchars($reg_player["player_Id"]) ?>">X</a>

                            <?php endif; ?>
                        </article>
                    <?php endforeach ?>

                </div>

        
            <?php elseif ($league_infos["playing_format"] === "doubles"): ?>

                <h2>Registrované dvojice</h2>
                
                <div class="players">

                    <?php foreach($registered_doubles as $reg_doubles_player): ?>
                        <article class="doubles-profil">

                        <?php if (htmlspecialchars($reg_doubles_player["player1_image"]) === "no-photo-player"): ?>
                            <div class="picture-part-doubles">
                                    <img src="../img/<?= htmlspecialchars($reg_doubles_player["player1_image"]) ?>.png" alt="">
                        <?php else: ?>
                            <div class="picture-part-doubles">
                                    <img src="../uploads/<?= htmlspecialchars($reg_doubles_player["player_Id_doubles_1"]) ?>/<?= htmlspecialchars($reg_doubles_player["player1_image"]) ?>" alt="">
                        <?php endif; ?>
                    
                                <div class="flag-part-doubles">
                                    <img src="../img/countries/<?= htmlspecialchars($reg_doubles_player["player1_country"]) ?>.png" alt="">
                                </div>
                            
                                <h6 class="profil-name"><?php echo htmlspecialchars($reg_doubles_player["player1_first_name"]). " ". htmlspecialchars($reg_doubles_player["player1_second_name"]) ?></h6>
                                <p class="profil-club"><?php echo htmlspecialchars($reg_doubles_player["player1_club"]) ?></p>
                                <a class="player-infos" href="./player-profil.php?player_Id=<?= htmlspecialchars($reg_doubles_player["player_Id_doubles_1"]) ?>">Informácie</a>
                            </div>
                        <?php if (htmlspecialchars($reg_doubles_player["player2_image"]) === "no-photo-player"): ?>
                            <div class="picture-part-doubles">
                                    <img src="../img/<?= htmlspecialchars($reg_doubles_player["player2_image"]) ?>.png" alt="">
                        <?php else: ?>
                            <div class="picture-part-doubles">
                                    <img src="../uploads/<?= htmlspecialchars($reg_doubles_player["player_Id_doubles_2"]) ?>/<?= htmlspecialchars($reg_doubles_player["player2_image"]) ?>" alt="">
                        <?php endif; ?>
                    
                                <div class="flag-part-doubles">
                                    <img src="../img/countries/<?= htmlspecialchars($reg_doubles_player["player2_country"]) ?>.png" alt="">
                                </div>
                            
                                <h6 class="profil-name"><?php echo htmlspecialchars($reg_doubles_player["player2_first_name"]). " ". htmlspecialchars($reg_doubles_player["player2_second_name"]) ?></h6>
                                <p class="profil-club"><?php echo htmlspecialchars($reg_doubles_player["player2_club"]) ?></p>
                                <a class="player-infos" href="./player-profil.php?player_Id=<?= htmlspecialchars($reg_doubles_player["player_Id_doubles_2"]) ?>">Informácie</a>
                            </div>
                            <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>

                                <a class="player-profilX" href="delete-doubles-in-league.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>&doubles_in_league_id=<?= htmlspecialchars($reg_doubles_player["doubles_in_league_id"]) ?>">X</a>

                            <?php endif; ?>
                        </article>
                    <?php endforeach ?>

                </div>

            
            <?php elseif ($league_infos["playing_format"] === "teams"): ?>
                <h2>Registrované družstvá</h2>
                

                <div class="players">

                    <?php foreach($registered_players as $reg_player): ?>
                        <article class="player-profil">

                        <?php if (htmlspecialchars($reg_player["team_image"]) === "no-photo-player"): ?>
                            <div class="picture-part" style="
                                    background: url(../img/<?= htmlspecialchars($reg_player["team_image"]) ?>.png);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                        <?php else: ?>
                            <div class="picture-part" style="
                                    background: url(../uploads/<?= htmlspecialchars($reg_player["team_id"]) ?>/<?= htmlspecialchars($reg_player["team_image"]) ?>);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                        <?php endif; ?>
                    
                                <div class="flag-part" style="
                                    background: url(../img/countries/<?= htmlspecialchars($reg_player["team_country"]) ?>.png);
                                    background-size: cover;
                                    background-position: center;
                                    background-repeat: no-repeat;
                                    ">
                                </div>
                            </div>
                            <h6 class="profil-name"><?php echo htmlspecialchars($reg_player["team_name"]) ?></h6>
                            
                            <a class="player-infos" href="./player-profil.php?team_id=<?= htmlspecialchars($reg_player["team_id"]) ?>">Informácie</a>

                            <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                                
                                <a class="player-profilX" href="delete-team-in-league.php?league_id=<?= htmlspecialchars($league_infos["league_id"]) ?>&team_id=<?= htmlspecialchars($reg_player["team_id"]) ?>">X</a>

                            <?php endif; ?>
                        </article>
                    <?php endforeach ?>

                </div>
            
            
            <?php endif; ?>   
            </div>   
            

        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
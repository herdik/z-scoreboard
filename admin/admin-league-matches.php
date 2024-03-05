<?php


require "../classes/Database.php";
require "../classes/Player.php";
require "../classes/League.php";
require "../classes/LeagueSettings.php";
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

if (isset($_GET["league_id"]) and is_numeric($_GET["league_id"])){
    $league_infos = League::getLeague($connection, $_GET["league_id"]);
    $league_id = $league_infos["league_id"];
    $count_groups = LeagueSettings::getLeagueSettings($connection, $league_id, "count_groups");
    $players_without_group = LeaguePlayer::getPlayerGroupInLeague($connection, $league_id);
    $players_in_group = LeaguePlayer::getAllLeaguePlayers($connection, $league_id, false);
} else {
    $league_infos = null;
    $registered_players = null;
    $count_groups = null;
    $players_without_group = null;
    $players_in_group = null;
}

$group_nr = 0;
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
    <link rel="stylesheet" href="../css/league-matches.css">
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
                <li><a href="#">Výsledky</a></li>
            </ul>

        </section>

        <section class="league-content">

            
            <?php if ($players_without_group != 0): ?>
            <div class="main-container-settings">
                
                <?php if ($count_groups["count_groups"] > 1): ?> 
                <h1>Liga skupiny</h1>
                <?php else: ?>
                <h1>Liga skupina</h1>
                <?php endif; ?>
                
                <?php if (($league_infos["manager_id"] === $_SESSION["logged_in_user_id"]) || ($_SESSION["role"] === "admin")): ?>
                    <form id="create-matches" action="after-league-matches.php" method="post">
                        <input type="hidden" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                        
                        <input type="submit" value="Vytvoriť">  

                    </form>
                <?php endif; ?>
            <?php else: ?>
            <div class="main-container-groups">
                <?php foreach ($players_in_group as $one_player): ?>
                    <?php if($one_player["league_group"] === 0): ?>
                    <?php elseif($one_player["league_group"] === $group_nr): ?>
                        <li><?= htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?></li>
                        <a href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>
                    
                    <?php else: ?>
                        <?php $group_nr++ ?>
                        </ul>
                        <ul class="one-modified-group">
                        <h3>Skupina č.<?= htmlspecialchars($one_player["league_group"]) ?></h3>
                            <li><?= htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?></li>
                            <a href="edit-league-group.php?player_in_league_id=<?= htmlspecialchars($one_player["player_in_league_id"]) ?>&league_id=<?= htmlspecialchars($one_player["league_id"]) ?>&league_group=0">x</a>

                    <?php endif ?>
                <?php endforeach ?>
                    </ul>    
                    <ul class="one-modified-group">
                    <h3>Nezaradení</h3>
                    <?php foreach ($players_in_group as $one_player): ?>
                        <?php if($one_player["league_group"] === 0): ?>
                        <li><?= htmlspecialchars($one_player["first_name"]) . " " . htmlspecialchars($one_player["second_name"])?></li>
                        <form action="./edit-league-group.php" method="POST">
                            <input type="hidden" name="player_in_league_id" value="<?= htmlspecialchars($one_player["player_in_league_id"]) ?>">
                            <input type="hidden" name="league_id" value="<?= htmlspecialchars($one_player["league_id"]) ?>">
                            <input type="number" name="league_group" class="select-groups" value="0" min="0" max="<?= htmlspecialchars($count_groups["count_groups"]) ?>">
                            <button>OK</button>
                        </form>
                        
                        <?php endif ?>
                    <?php endforeach ?>
                    </ul>

                <form action="./after-finished-league-groups.php" method="post">
                <input type="hidden" name="league_id" value="<?php echo $league_id; ?>">
                <input type="submit" value="Zahájiť ligu">
            </form>
                    
            <?php endif ?>

            </div>
            
        </section>

        
    </main>

    <?php require "../assets/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>
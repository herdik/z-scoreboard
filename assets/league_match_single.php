<div class="general-match <?= htmlspecialchars($match_color) ?>">
                                
    <!-- print profil image for player1 -->
    <?php if ($league_match["player1_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_1"]) . "/" . htmlspecialchars($league_match["player1_image"]) ?> alt="">
    <?php endif ?> 

    <span class="pl1-span">

            <!-- print coutry flag for player1 -->
        <?php if ($league_match["player1_country"] === "none" && $league_match["player1_firstname"] === "Voľno"): ?>
        <?php else: ?>
            <img src="../img/countries/<?= htmlspecialchars($league_match["player1_country"]) ?>.png" alt="">
        <?php endif ?>
            <!-- Player name -->
            <?= htmlspecialchars($league_match["player1_firstname"]) . " " . htmlspecialchars($league_match["player1_second_name"]) ?>

            <!-- club info -->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player1_club"]) ?>
            </div> 

    </span>

    <label class="pl1-label"><?= htmlspecialchars($league_match["score_1"]) ?></label>

    <div class="btnAndGame">
        <img src=<?="../img/" . htmlspecialchars($league_match["choosed_game"]). "-ball.png" ?> alt=<?= htmlspecialchars($league_match["choosed_game"]). "-ball" ?>>

        <?php if ($button_text === "Zapnúť"): ?>

            <!-- <form id="status-match" action="./change-status-match.php" method="post"> -->
                <!-- <button name="match_button" type="submit" value="Zapnúť"></button> -->
    
        <?php elseif ($button_text === "Čaká"): ?>
            <!-- <form id="status-match" action="./change-status-match.php" method="post"> -->
                
        <?php endif ?>
                <input type="hidden" class="match_id" name="match_id" value="<?= htmlspecialchars($league_match["match_id"]) ?>" readonly>
                <input type="hidden" class="league_id" name="league_id" value="<?= htmlspecialchars($league_infos["league_id"]) ?>" readonly>
                <input type="hidden" class="league_group" name="league_group" value="<?= htmlspecialchars($league_group) ?>" readonly>
                <input class="match_button <?php if($league_match["match_finished"]) { echo 'fisnishedLeagueMatchHide'; } ?>" name="btn_value" type="submit" value="<?= htmlspecialchars($button_text) ?>">
                <a href="#"></a>
            <!-- </form> -->
    </div>

    <label class="pl2-label"><?= htmlspecialchars($league_match["score_2"]) ?></label>
    
    <span class="pl2-span">

        <!-- print coutry flag for player2 -->
        <?php if ($league_match["player2_country"] === "none" && $league_match["player2_firstname"] === "Voľno"): ?>
        <?php else: ?>
            <img src="../img/countries/<?= htmlspecialchars($league_match["player2_country"]) ?>.png" alt="">
        <?php endif ?>  
            <!-- Player name -->      
            <?= htmlspecialchars($league_match["player2_firstname"]) . " " . htmlspecialchars($league_match["player2_second_name"]) ?>

            <!-- club info -->
            <div class="main-info-player">    
                <?= htmlspecialchars($league_match["player2_club"]) ?>
            </div> 
    </span>

    <!-- print profil image for player -->
    <?php if ($league_match["player2_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["player_id_2"]) . "/" . htmlspecialchars($league_match["player2_image"]) ?> alt="">
    <?php endif ?>
    
</div>                                   
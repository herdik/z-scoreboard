<div class="general-match">
                                
    <!-- print profil image for player1 -->
    <?php if ($league_match["team1_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["team_id_1"]) . "/" . htmlspecialchars($league_match["team1_image"]) ?> alt="">
    <?php endif ?> 

    <span class="pl1-span">

            <!-- print coutry flag for player1 -->
        <?php if ($league_match["team1_country"] === "none" && $league_match["team1_name"] === "Voľno"): ?>
        <?php else: ?>
            <img src="../img/countries/<?= htmlspecialchars($league_match["team1_country"]) ?>.png" alt="">
        <?php endif ?>
            <!-- Player name -->
            <?= htmlspecialchars($league_match["team1_name"]) ?>

            <!-- club info --> 

    </span>

    <label class="pl1-label"><?= htmlspecialchars($league_match["score_1"]) ?></label>

    <div class="btnAndGame">
        <img src=<?="../img/" . htmlspecialchars($league_match["choosed_game"]). "-ball.png" ?> alt=<?= htmlspecialchars($league_match["choosed_game"]). "-ball" ?>>
        <button>Zapnúť</button>
    </div>

    <label class="pl2-label"><?= htmlspecialchars($league_match["score_2"]) ?></label>
    
    <span class="pl2-span">

        <!-- print coutry flag for player2 -->
        <?php if ($league_match["team2_country"] === "none" && $league_match["team2_name"] === "Voľno"): ?>
        <?php else: ?>
            <img src="../img/countries/<?= htmlspecialchars($league_match["team2_country"]) ?>.png" alt="">
        <?php endif ?>  
            <!-- Player name -->      
            <?= htmlspecialchars($league_match["team2_name"]) ?>

            <!-- club info -->
    </span>

    <!-- print profil image for player -->
    <?php if ($league_match["team2_image"] === "no-photo-player"): ?>
        <img class="league-profil" src="../img/no-photo-player.png" alt=""> 
    <?php else: ?>
        <img class="league-profil" src=<?= "../uploads/" . htmlspecialchars($league_match["team_id_2"]) . "/" . htmlspecialchars($league_match["team2_image"]) ?> alt="">
    <?php endif ?>
    
</div>
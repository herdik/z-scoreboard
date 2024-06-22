<input type="email" name="fake_email" style="display:none" aria-hidden="true">
<input type="email" autofocus name="user_email" placeholder="Email" value="<?= htmlspecialchars($player_infos["user_email"]) ?>" autocomplete=“off” required><br>
<input type="text" name="first_name" placeholder="Meno" value="<?= htmlspecialchars($player_infos["first_name"]) ?>" autocomplete=“off” required>
<input type="text" name="second_name" placeholder="Priezvisko" value="<?= htmlspecialchars($player_infos["second_name"]) ?>" autocomplete=“off” required><br>
<input type="text" name="country" placeholder="Krajina" value="<?= htmlspecialchars($player_infos["country"]) ?>" autocomplete=“off” required>

<?php if (htmlspecialchars($player_infos["player_club"]) === ""): ?>

  <input type="text" id="answerInput" name="player_club" placeholder="Klub" list="player_clubs" autocomplete=“off” required><br>

  <datalist id="player_clubs">
  <?php foreach ($teams_infos as $one_team_info): ?>
    <option data-value="<?= htmlspecialchars($one_team_info["team_id"]) ?>"><?= htmlspecialchars($one_team_info["team_name"]) ?></option>
  <?php endforeach ?>
  </datalist><br>

  <input type="hidden" name="player_club_id" id="answerInput-hidden">

<?php else: ?>

  <input type="text" id="answerInput" name="player_club" placeholder="Klub" list="player_clubs" autocomplete=“off” value="<?= htmlspecialchars($player_infos["player_club"]) ?>" required><br>

  <datalist id="player_clubs">
  <?php foreach ($teams_infos as $one_team_info): ?>
    <option data-value="<?= htmlspecialchars($one_team_info["team_id"]) ?>"><?= htmlspecialchars($one_team_info["team_name"]) ?></option>
  <?php endforeach ?>
  </datalist><br>

  <input type="hidden" name="player_club_id" value="<?= htmlspecialchars($player_infos["player_club_id"]) ?>" id="answerInput-hidden">
<?php endif ?>


<a href="./image-gallery.php?player_Id=<?= htmlspecialchars($player_infos["player_Id"]) ?>" id="IMGGallery">Obrázok z galérie</a>
<label for="playerIMG" id="choose-img-text">Vybrať obrázok</label>
<?php if (htmlspecialchars($image_sequence) == NULL): ?>
  <p>text</p>
<?php else: ?>
  <p style="opacity:1;">Zvolený obrázok: Obrázok č.<?= htmlspecialchars($image_sequence) ?></p>
<?php endif; ?>
<input type="hidden" name="image_id" value="<?= htmlspecialchars($image_id) ?>" readonly>
<input id="playerIMG" type="file" name="player_Image"><br>
<input type="text" name="player_cue" placeholder="Hracie tágo" value="<?= htmlspecialchars($player_infos["player_cue"]) ?>" autocomplete="off"><br>
<input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo" value="<?= htmlspecialchars($player_infos["player_break_cue"]) ?>" autocomplete="off">
<input type="text" name="player_jump_cue" placeholder="Skákacie tágo" value="<?= htmlspecialchars($player_infos["player_jump_cue"]) ?>" autocomplete="off"><br>
<input type="text" name="user_email" placeholder="Email" value="<?= htmlspecialchars($player_infos["user_email"]) ?>" required>
<input type="text" name="first_name" placeholder="Meno" value="<?= htmlspecialchars($player_infos["first_name"]) ?>" required>
<input type="text" name="second_name" placeholder="Priezvisko" value="<?= htmlspecialchars($player_infos["second_name"]) ?>" required>
<input type="text" name="country" placeholder="Krajina" value="<?= htmlspecialchars($player_infos["country"]) ?>" required>
<input type="text" name="player_club" autocomplete=“off” placeholder="Klub" value="<?= htmlspecialchars($player_infos["player_club"]) ?>" list="player_clubs" required>
<datalist id="player_clubs">
  <option>BK MANILA Topoľčany</option>
  <option>POINT Trenčín</option>
  <option>BK Aréna Ružomberok</option>
</datalist>
<input type="text" name="player_Image" placeholder="Obrázok" value="<?= htmlspecialchars($player_infos["player_Image"]) ?>">
<input type="text" name="player_cue" placeholder="Hracie tágo" value="<?= htmlspecialchars($player_infos["player_cue"]) ?>">
<input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo" value="<?= htmlspecialchars($player_infos["player_break_cue"]) ?>">
<input type="text" name="player_jump_cue" placeholder="Skákacie tágo" value="<?= htmlspecialchars($player_infos["player_jump_cue"]) ?>">
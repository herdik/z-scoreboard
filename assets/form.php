<input type="email" name="fake_email" style="display:none" aria-hidden="true">
<input type="email" autofocus name="user_email" placeholder="Email" value="<?= htmlspecialchars($player_infos["user_email"]) ?>" autocomplete=“off” required><br>
<input type="text" name="first_name" placeholder="Meno" value="<?= htmlspecialchars($player_infos["first_name"]) ?>" autocomplete=“off” required>
<input type="text" name="second_name" placeholder="Priezvisko" value="<?= htmlspecialchars($player_infos["second_name"]) ?>" autocomplete=“off” required><br>
<input type="text" name="country" placeholder="Krajina" value="<?= htmlspecialchars($player_infos["country"]) ?>" autocomplete=“off” required>
<input type="text" name="player_club" placeholder="Klub" value="<?= htmlspecialchars($player_infos["player_club"]) ?>" list="player_clubs" autocomplete=“off” required><br>
<datalist id="player_clubs">
  <option>BK MANILA Topoľčany</option>
  <option>POINT Trenčín</option>
  <option>BK Aréna Ružomberok</option>
</datalist><br>
<label for="playerIMG" id="choose-img-text">Vybrať obrázok</label>
<p>text</p>
<input id="playerIMG" type="file" name="player_Image"><br>
<input type="text" name="player_cue" placeholder="Hracie tágo" value="<?= htmlspecialchars($player_infos["player_cue"]) ?>" autocomplete="off"><br>
<input type="text" name="player_break_cue" placeholder="Rozbíjacie tágo" value="<?= htmlspecialchars($player_infos["player_break_cue"]) ?>" autocomplete="off">
<input type="text" name="player_jump_cue" placeholder="Skákacie tágo" value="<?= htmlspecialchars($player_infos["player_jump_cue"]) ?>" autocomplete="off"><br>
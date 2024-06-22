<?php

?>


<!DOCTYPE html>
<html>
<body>

<h1>The datalist element</h1>

<form action="/action_page.php" method="get">
  <label for="browser">Choose your browser from the list:</label>
  <input list="cars" value="BMW" class="form-control" name="caBrands" style="width:300px;">
 <datalist id="cars">
 <option value="BMW">
 <option value="Toyota">
 <option value="Mitsubishi">
 </datalist>
  <input type="submit">
</form>

<p><strong>Note:</strong> The datalist tag is not supported in Safari 12.0 (or earlier).</p>

</body>
</html>
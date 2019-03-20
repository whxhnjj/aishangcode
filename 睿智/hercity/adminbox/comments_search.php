<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>


<div id="path">
loftCMS -> ÆÀÂÛ¹ÜÀí -> ËÑË÷
</div>

<form name="form1" action="comments_index.php" method="get" class="form_search">
<label for="key">¹Ø¼ü´Ê£º</label>
<input type="text" name="key" />
<input type="submit" name="submit" value="ËÑË÷" />
</form>

<?php
require_once("includes/footer.php");
?>
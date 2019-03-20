<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 任务管理 -> 清除系统垃圾");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_clear_attachments.php" target="_self">1.清除游离附件</a>
</div>

<?php
require_once("includes/footer.php");
?>
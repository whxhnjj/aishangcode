<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 任务管理 -> 更新HTML页面");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_write_show.php" target="_self">1.更新文章页</a>
<a href="crons_write_list.php" target="_self">2.更新列表页</a>
<a href="crons_write_html.php" target="_self">3.更新首页及频道</a>
<a href="crons_write_thumb.php" target="_self">4.更新影像缩略图</a>
</div>

<?php
require_once("includes/footer.php");
?>
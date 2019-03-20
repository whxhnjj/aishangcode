<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 任务管理 -> 更新数据统计");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_update_comments.php" target="_self">1.更新文章评论数</a>
<a href="crons_update_persons_baidu_index.php" target="_self">3.更新人物百度指数</a>
</div>

<?php
require_once("includes/footer.php");
?>
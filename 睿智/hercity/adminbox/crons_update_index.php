<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ������� -> ��������ͳ��");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_update_comments.php" target="_self">1.��������������</a>
<a href="crons_update_persons_baidu_index.php" target="_self">3.��������ٶ�ָ��</a>
</div>

<?php
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ������� -> ���ϵͳ����");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_clear_attachments.php" target="_self">1.������븽��</a>
</div>

<?php
require_once("includes/footer.php");
?>
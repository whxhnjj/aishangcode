<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/header.php");

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ������� -> ����HTMLҳ��");
echo ("</div>\n\n");
?>

<div class="cron_box">
<a href="crons_write_show.php" target="_self">1.��������ҳ</a>
<a href="crons_write_list.php" target="_self">2.�����б�ҳ</a>
<a href="crons_write_html.php" target="_self">3.������ҳ��Ƶ��</a>
<a href="crons_write_thumb.php" target="_self">4.����Ӱ������ͼ</a>
</div>

<?php
require_once("includes/footer.php");
?>
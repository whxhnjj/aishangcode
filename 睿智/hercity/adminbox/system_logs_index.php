<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."logs")->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ��־���� -> ��¼��־");

echo ("<ul id=\"tools\">");
echo ("<li><a id=\"logs_del\" class=\"todo\">���</a></li>");
echo ("</ul>");

echo ("</div>\n\n");







//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:80px;">��¼��</th>');
echo('<th style="width:140px;">IP</th>');
echo('<th>����ϵͳ</th>');
echo('<th style="width:140px;">ʱ��</th>');
echo('<th style="width:60px;">�ɹ���</th>');
echo('</tr>');

$sql = "select id,uname,ip,sys,dateline,is_success from ".$config['tablePre']."logs order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while($rs = $result->fetch_array())
{
$icon_success = $rs["is_success"]?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$rs["id"]);
printf('<td>%d</td>',$rs["id"]);
printf('<td>%s</td>',$rs["uname"]);
printf('<td>%s</td>',$rs["ip"]);
printf('<td>%s</td>',$rs["sys"]);
printf('<td>%s</td>',date("Y-m-d H:i",$rs["dateline"]));
printf('<td>%s</td>',$icon_success);
echo('</tr>');
}
echo('</table>');



$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
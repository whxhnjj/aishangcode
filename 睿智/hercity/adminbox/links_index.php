<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


$cat = _GET('cat','i');

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."links where cat=".$cat)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ���ӹ��� -> ".$config['linkCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"links_new.php?cat=%d\">�½�</a></li>",$cat);
echo ("<li><a id=\"links_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");







//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:80px;">����Ȩֵ</th>');
echo('<th style="width:180px;text-align:left;">����</th>');
echo('<th style="text-align:left;">����</th>');
echo('<th style="width:80px;">����</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');

$sql = "select id,title,linkto,order_id,hits from ".$config['tablePre']."links where cat=".$cat." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while(list($id,$title,$linkto,$order_id,$hits) = $result->fetch_row())
{
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$order_id);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td style="text-align:left;"><a href="%s">%s</a></td>',$linkto,substr($linkto,0,50));
printf('<td>%d</td>',$hits);
printf('<td><a href="links_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$id);
echo('</tr>');
}
echo('</table>');






$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
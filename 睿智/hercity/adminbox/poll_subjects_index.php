<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."poll_subjects")->fetch_row();

//������
echo ("<div id=\"path\">");

echo ("<ul id=\"tools\">");
echo ("<li><a href=\"poll_subjects_new.php\">�½�</a></li>");
echo ("<li><a id=\"poll_subjects_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("loftCMS -> ͶƱ���� -> ͶƱ����");

echo ("</div>\n\n");









//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:90px;">��������ID</th>');
echo('<th style="text-align:left;">ͶƱ����</th>');
echo('<th style="width:140px;">����ʱ��</th>');
echo('<th style="width:80px;">�޸�</th>');
echo('<th style="width:80px;">����ѡ��</th>');
echo('</tr>');


$sql = "select id,post_id,title,expiration from ".$config['tablePre']."poll_subjects order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$post_id,$title,$expiration) = $result->fetch_row())
{
$expiration=$expiration?date("Y-m-d H:i:s",$expiration):"����";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$post_id);
printf('<td style="text-align:left;"><a href="poll_options_index.php?subject_id=%d">%s</a></td>',$id,$title);
printf('<td>%s</td>',$expiration);
printf('<td><a href="poll_subjects_edit.php?id=%d">�޸�</a></td>',$id);
printf('<td><a href="poll_options_index.php?subject_id=%d">����ѡ��</a></td>',$id);
echo('</tr>');
}
echo('</table>');



$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
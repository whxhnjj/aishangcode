<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//��ȡ����id
$subject_id = _GET('subject_id','i');

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."poll_options where subject_id=".$subject_id)->fetch_row();

//������
echo ("<div id=\"path\">");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"poll_options_new.php?subject_id=%d\">�½�</a></li>",$subject_id);
echo ("<li><a id=\"poll_options_show\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"poll_options_not_show\" class=\"todo\">ȡ������</a></li>");
echo ("<li><a id=\"poll_options_del\" class=\"todo\">ɾ��</a></li>");
echo ("<li><a href=\"poll_subjects_index.php\" >����ͶƱ����</a></li>");
echo ("</ul>");
echo ("loftCMS -> ͶƱ���� -> ��ѡ�����");
echo ("</div>\n\n");




//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:80px;">���</th>');
echo('<th>����</th>');
echo('<th style="width:140px;">��Ʊ��</th>');
echo('<th style="width:40px;">״̬</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');


$sql = "select id,number,title,votes,is_show from ".$config['tablePre']."poll_options where subject_id=".$subject_id." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$number,$title,$votes,$is_show) = $result->fetch_row())
{

$icon_show = $is_show?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>NO.%d</td>',$number);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td>%s</td>',$votes);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="poll_options_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$id);
echo('</tr>');
}
echo('</table>');


$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
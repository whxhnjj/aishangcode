<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize = 20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."attachments")->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> �������� -> �����б�");

echo ("<ul id=\"tools\">");
echo ("<li><a id=\"attachments_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th>����</th>');
echo('<th style="width:80px;">��������ID</th>');
echo('<th style="width:200px;">�ļ���</th>');
echo('<th style="width:80px;">�ļ���С</th>');
echo('<th style="width:200px;">�ļ�����</th>');
echo('<th style="width:80px;">�Ƿ�ͼƬ</th>');
echo('<th style="width:80px;">�޸�</th>');
echo('</tr>');


$sql = "select id,post_id,title,filename,filesize,filetype,dateline,is_img from ".$config['tablePre']."attachments order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$post_id,$title,$filename,$filesize,$filetype,$dateline,$is_img) = $result->fetch_row())
{
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%s</td>',$title);
printf('<td>%d</td>',$post_id);
printf('<td style="text-align:left;"><a href="%s" target="_blank">%s</a></td>',$config['basePath'].$config['attachmentPath'].$filename,$filename);
printf('<td>%s</td>',round($filesize/1024,2)."k");
printf('<td>%s</td>',$filetype);
printf('<td>%s</td>',$is_img);
printf('<td><a href="attachments_edit.php?id=%d">�޸�</a></td>',$id);
echo('</tr>');
}
echo('</table>');











$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//��ȡ�û���
$usergroup = _GET('usergroup','i');

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."users where usergroup=".$usergroup)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> �û����� -> �û��б�");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"users_new.php?usergroup=%d\">�½�</a></li>",$usergroup);
echo ("<li><a id=\"users_active\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"users_not_active\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"users_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");







//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th>�û���</th>');
echo('<th style="width:80px;">�ǳ�</th>');
echo('<th style="width:200px;">ע��ʱ��</th>');
echo('<th style="width:80px;">��¼����</th>');
echo('<th style="width:200px;">�ƹ����</th>');
echo('<th style="width:80px;">״̬</th>');
echo('<th style="width:80px;">�޸�</th>');
echo('</tr>');


$sql = "select id,uname,nickname,dateline,logintimes,is_active,point from ".$config['tablePre']."users where usergroup=".$usergroup." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while($rs = $result->fetch_array())
{
$icon_active = $rs['is_active']?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$rs['id']);
printf('<td>%d</td>',$rs['id']);
printf('<td>%s</td>',$rs['uname']);
printf('<td>%s</td>',$rs['nickname']);
printf('<td>%s</td>',date("Y-m-d H:i:s",$rs['dateline']));
printf('<td>%d</td>',$rs['logintimes']);
printf('<td>%d</td>',$rs['point']);
printf('<td>%s</td>',$icon_active);
printf('<td><a href="users_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$rs['id']);
echo('</tr>');
}
echo('</table>');




$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
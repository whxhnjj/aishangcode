<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//ȷ������������ȫ���г�
$key = _GET('key','s');
$str_where = $key?" where content like '%".$key."%' ":"" ;
$page_title = $key?"������".$key."���Ľ��":"�����б�" ;

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."comments ".$str_where)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ���۹��� -> ".$page_title);

echo ("<ul id=\"tools\">");
echo ("<li><a id=\"comments_show\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"comments_not_show\" class=\"todo\">ȡ������</a></li>");
echo ("<li><a id=\"comments_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:50px;">��ԱID</th>');
echo('<th style="width:140px;">����</th>');
echo('<th>����</th>');
echo('<th style="width:140px;">ʱ��</th>');
echo('<th style="width:80px;">����ID</th>');
echo('<th style="width:80px;">״̬</th>');
echo('</tr>');


$sql = "select id,uid,name,content,dateline,post_id,is_show,email from ".$config['tablePre']."comments ".$str_where." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$uid,$name,$content,$dateline,$post_id,$is_show,$email) = $result->fetch_row())
{
$content = str_replace('<br>',' / ',$content);
$content = str_replace('<br />',' / ',$content);
$content = mb_substr($content,0,50,'gbk')."��";

$icon_show = $is_show ? "<span class=\"yes\">��</span>" : "<span class=\"no\">��</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$uid);
printf('<td>%s</td>',$name);
printf('<td style="text-align:left;">%s</td>',$content);
printf('<td>%s</td>',date("Y-m-d h:i",$dateline));
printf('<td><a href="%s?id=%d" target="_blank">%d</a></td>',$config['basePath'].$config['phpShowPath'],$post_id,$post_id);
printf('<td>%s</td>',$icon_show);
echo('</tr>');
}
echo('</table>');



$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
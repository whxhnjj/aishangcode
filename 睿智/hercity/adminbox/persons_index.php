<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//��ȡ��Ŀid
$cat = _GET('cat','i');

//��ȡ��������
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//�϶����������ǰ���Ŀ�г���
if ($cat)
{
$str_where =  " where cat=".$cat ;
$page_title = "�����б�" ;
}
else if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "������".$search_key."���Ľ��" ;
}
else
{
$str_where = " where ".$search_field." = '' " ;
$page_title = "������".$search_key."���Ľ��" ;
}




//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."persons".$str_where)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ������� -> ".$config['personCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"persons_new.php?cat=%d\">�½�</a></li>",$cat);
echo ("<li id=\"search\"><a href=\"persons_search.php\">����</a>");
echo ("<li><a id=\"persons_show\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"persons_not_show\" class=\"todo\">ȡ������</a></li>");
echo ("<li><a id=\"persons_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");





//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:150px;">����</th>');
echo('<th style="width:150px;">Ӣ����</th>');
echo('<th style="width:60px;">����</th>');
echo('<th style="width:60px;">��˿</th>');
echo('<th style="width:60px;">�ٶ�ָ��</th>');
echo('<th style="text-align:left;">����</th>');
echo('<th style="width:40px;">״̬</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');


$sql = "select id,name,ename,hits,fans,baidu_index,title,is_show from ".$config['tablePre']."persons".$str_where." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while(list($id,$name,$ename,$hits,$fans,$baidu_index,$title,$is_show) = $result->fetch_row())
{
$icon_show = $is_show?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%s <a href="http://www.baidu.com/s?wd=%s" target="_blank">�ٶ�</a></td>',$name,$name);
printf('<td>%s</td>',$ename);
printf('<td>%s</td>',$hits);
printf('<td>%d</td>',$fans);
printf('<td>%d</td>',$baidu_index);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="persons_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$id);
echo('</tr>');
}
echo('</table>');



$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
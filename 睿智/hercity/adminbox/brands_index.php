<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//��ȡ��������
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//�϶����������ǰ���Ŀ�г���

$page_title = "Ʒ���б�" ;

if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "������".$search_key."���Ľ��" ;
}


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."brands".$str_where)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> Ʒ�ƹ��� -> ".$config['personCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"brands_new.php?cat=%d\">�½�</a></li>",$cat);
echo ("<li id=\"search\"><a href=\"brands_search.php\">����</a>");
echo ("<li><a id=\"brands_show\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"brands_not_show\" class=\"todo\">ȡ������</a></li>");
echo ("<li><a id=\"brands_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:40px;">����</th>');
echo('<th>Ʒ������</th>');
echo('<th style="width:50px;">����</th>');
echo('<th style="width:70px;">�Ƿ���֤</th>');
echo('<th style="width:150px;">�绰</th>');
echo('<th style="width:120px;">QQ</th>');
echo('<th style="width:40px;">����</th>');
echo('<th style="width:40px;">�ȶ�</th>');
echo('<th style="width:40px;">����</th>');
echo('<th style="width:40px;">״̬</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');


$sql = "select * from ".$config['tablePre']."brands".$str_where." order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while($brand = $result->fetch_array())
{
$icon_auth = $brand['is_auth']?"<span class=\"yes\">����֤</span>":"<span class=\"no\">δ��֤</span>";
$icon_show = $brand['is_show']?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";
$name = sprintf("%s %s (%s)",$brand['firstname'],$brand['lastname'],$brand['ename']);
if ($brand['order_id'] > 0)
{
$name = '<span class="red bold">'.$name.'</span>';
}
if ($brand['order_id'] < 0)
{
$name = '<span class="green bold">'.$name.'</span>';
}

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$brand['id']);
printf('<td>%d</td>',$brand['id']);
printf('<td>%d</td>',$brand['order_id']);
printf('<td style="text-align:left;">%s</td>',$name);
printf('<td>%s</td>',$brand['domain']);
printf('<td>%s</td>',$icon_auth);
printf('<td>%s</td>',$brand['tel']);
printf('<td>%s</td>',$brand['qq']);
printf('<td>%d</td>',$brand['hits']);
printf('<td>%d</td>',$brand['hots']);
printf('<td>%d</td>',$brand['days']);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="brands_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$brand['id']);
echo('</tr>');
}
echo('</table>');





$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
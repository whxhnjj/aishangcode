<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//��ȡ��������
$search_field = _GET('search_field');
$search_key = _GET('search_key');


$str_where = " where 1=1 ";
$page_title = "ȫ��";

if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "������".$search_key."���Ľ��" ;
}


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."keywords ".$str_where)->fetch_row();

//������
echo ("<div id=\"path\">");
echo ("loftCMS -> �ؼ��ʹ��� ");

echo ("<ul id=\"tools\">");
echo ("<li><a href=\"keywords_search.php\">����</a></li>");
echo ("<li><a href=\"keywords_new.php\">�½�</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:100px;">ƴ��</th>');
echo('<th>��ǩ��</th>');
echo('<th style="width:80px;">���ô���</th>');
echo('<th style="width:80px;">�޸�</th>');
echo('</tr>');


$sql = "select id,name,ename,hits from ".$config['tablePre']."keywords ".$str_where." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$name,$ename,$hits) = $result->fetch_row())
{
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td style="text-align:left;">%s</td>',$ename);
printf('<td style="text-align:left;"><a href="/tag/?q=%s" target="_blank">%s</a></td>',$name,$name);
printf('<td>%d</td>',$hits);
printf('<td><a href="keywords_edit.php?id=%d" class="icon_edit">�޸�</a></td>',$id);
echo('</tr>');
}
echo('</table>');







$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
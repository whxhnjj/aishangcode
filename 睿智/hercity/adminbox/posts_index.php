<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");



//��ȡ��������
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//��ȡ��Ŀid
$kind_id = _GET('kind_id','i');

//�϶����������ǰ���Ŀ�г���
if ($kind_id)
{
$str_where =  " where kind_id=".$kind_id ;
$kindsGetInfo = kindsGetInfo($kind_id);
$page_title = $kindsGetInfo['name'];
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
$pagesize = 20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts ".$str_where)->fetch_row();

//ȡ�ñ�ҳurl�Ա㷵��ʱ�á�
$current_url = urlEncode($_SERVER['REQUEST_URI']);

//������
echo ("<div id=\"path\">");
echo ("<ul id=\"tools\">");
printf ("<li id=\"new\"><a href=\"posts_new.php?kind_id=%d&u=%s\">�½�</a>",$kind_id,str_replace('page%3D','',$current_url));

//�½����Ӳ˵�
echo ("<ol class=\"submenu\">");
while ($sm = each($config['specialModel']))
{
printf ("<li><a href=\"posts_new.php?kind_id=%d&special=%d&u=%s\">%s</a></li>",$kind_id,$sm[0],str_replace('page%3D','',$current_url),$sm[1]);
}
echo("</ol>");
echo("</li>");

echo ("<li id=\"search\"><a href=\"posts_search.php\">����</a></li>");

if ($kind_id > 0) printf ("<li><a href=\"posts_write_list.php?kind_id=%d\">���±���Ŀҳ</a></li>",$kind_id);
echo ("<li><a id=\"posts_show\" class=\"todo\">����</a></li>");
echo ("<li><a id=\"posts_not_show\" class=\"todo\">ȡ������</a></li>");
echo ("<li><a id=\"posts_del\" class=\"todo\">ɾ��</a></li>");
if (file_exists('plugins/taobaoke') && isset($config['taobaoke']['appkey'])){printf ("<li id=\"taobaoke\"><a href=\"plugins/taobaoke/list.php?kind_id=%d\">�Ա���</a></li><li id=\"taobaoke\"><a href=\"plugins/taobaoke/checkitems.php?kind_id=%d\">����¼���Ʒ</a></li>",$kind_id,$kind_id);}
echo ("</ul>");

echo ("loftCMS -> ���¹��� -> ".$page_title);
echo ("</div>\n\n");



//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:50px;">����</th>');
echo('<th>����</th>');
echo('<th style="width:140px;">����ʱ��</th>');
echo('<th style="width:80px;">������</th>');
echo('<th style="width:80px;">�վ�����</th>');
echo('<th style="width:50px;">�༭ID</th>');
echo('<th style="width:40px;">״̬</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');


$sql = "select id,title,dateline,hits,point,is_show,order_id,arrposid,special,user_id from ".$config['tablePre']."posts ".$str_where." order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$title,$dateline,$hits,$point,$is_show,$order_id,$arrposid,$special,$user_id) = $result->fetch_row())
{
$order_id = $order_id ? "<strong class=\"red\">$order_id</strong>" : $order_id;
$arrpos_style = $arrposid ? " class=\"bold orange\" title=\"$arrposid\" " : "";
$special_sign = ($special == -1) ? "<span class=\"icon_link\"></span>" : '';
$title = "<a href=\"".$config['basePath'].$config['phpShowPath']."?id=$id\" target=\"_blank\" $arrpos_style >$title</a> $special_sign";
$date = date("Y-m-d H:i",$dateline);
$hitsperday = $hits/(time()-$dateline)*3600*24;
$hitsperday_format = number_format($hitsperday,2);
if ($hitsperday >= 100) {$hitsperday_format = '<span class="orange">'.$hitsperday_format.'</span>';}
$icon_show = $is_show?"<span class=\"yes\">��</span>":"<span class=\"no\">��</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%s</td>',$order_id);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td>%s</td>',$date);
printf('<td>%d</td>',$hits);
printf('<td>%d</td>',$hitsperday_format);
printf('<td>%d</td>',$user_id);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="posts_edit.php?id=%d&u=%s" class="icon_edit">�޸�</a></td>',$id,$current_url);
echo('</tr>');
}
echo('</table>');

$result->free();

//���÷�ҳ����
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
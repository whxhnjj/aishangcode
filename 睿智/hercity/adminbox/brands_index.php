<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//获取搜索条件
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//断断是搜索还是按栏目列出。

$page_title = "品牌列表" ;

if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "搜索“".$search_key."”的结果" ;
}


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."brands".$str_where)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 品牌管理 -> ".$config['personCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"brands_new.php?cat=%d\">新建</a></li>",$cat);
echo ("<li id=\"search\"><a href=\"brands_search.php\">搜索</a>");
echo ("<li><a id=\"brands_show\" class=\"todo\">发布</a></li>");
echo ("<li><a id=\"brands_not_show\" class=\"todo\">取消发布</a></li>");
echo ("<li><a id=\"brands_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:40px;">排序</th>');
echo('<th>品牌名称</th>');
echo('<th style="width:50px;">域名</th>');
echo('<th style="width:70px;">是否认证</th>');
echo('<th style="width:150px;">电话</th>');
echo('<th style="width:120px;">QQ</th>');
echo('<th style="width:40px;">人气</th>');
echo('<th style="width:40px;">热度</th>');
echo('<th style="width:40px;">天数</th>');
echo('<th style="width:40px;">状态</th>');
echo('<th style="width:40px;">修改</th>');
echo('</tr>');


$sql = "select * from ".$config['tablePre']."brands".$str_where." order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while($brand = $result->fetch_array())
{
$icon_auth = $brand['is_auth']?"<span class=\"yes\">已认证</span>":"<span class=\"no\">未认证</span>";
$icon_show = $brand['is_show']?"<span class=\"yes\">√</span>":"<span class=\"no\">×</span>";
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
printf('<td><a href="brands_edit.php?id=%d" class="icon_edit">修改</a></td>',$brand['id']);
echo('</tr>');
}
echo('</table>');





$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
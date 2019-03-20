<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");



//获取搜索条件
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//获取栏目id
$kind_id = _GET('kind_id','i');

//断断是搜索还是按栏目列出。
if ($kind_id)
{
$str_where =  " where kind_id=".$kind_id ;
$kindsGetInfo = kindsGetInfo($kind_id);
$page_title = $kindsGetInfo['name'];
}
else if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "搜索“".$search_key."”的结果" ;
}
else
{
$str_where = " where ".$search_field." = '' " ;
$page_title = "搜索“".$search_key."”的结果" ;
}


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize = 20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts ".$str_where)->fetch_row();

//取得本页url以便返回时用。
$current_url = urlEncode($_SERVER['REQUEST_URI']);

//引导栏
echo ("<div id=\"path\">");
echo ("<ul id=\"tools\">");
printf ("<li id=\"new\"><a href=\"posts_new.php?kind_id=%d&u=%s\">新建</a>",$kind_id,str_replace('page%3D','',$current_url));

//新建的子菜单
echo ("<ol class=\"submenu\">");
while ($sm = each($config['specialModel']))
{
printf ("<li><a href=\"posts_new.php?kind_id=%d&special=%d&u=%s\">%s</a></li>",$kind_id,$sm[0],str_replace('page%3D','',$current_url),$sm[1]);
}
echo("</ol>");
echo("</li>");

echo ("<li id=\"search\"><a href=\"posts_search.php\">搜索</a></li>");

if ($kind_id > 0) printf ("<li><a href=\"posts_write_list.php?kind_id=%d\">更新本栏目页</a></li>",$kind_id);
echo ("<li><a id=\"posts_show\" class=\"todo\">发布</a></li>");
echo ("<li><a id=\"posts_not_show\" class=\"todo\">取消发布</a></li>");
echo ("<li><a id=\"posts_del\" class=\"todo\">删除</a></li>");
if (file_exists('plugins/taobaoke') && isset($config['taobaoke']['appkey'])){printf ("<li id=\"taobaoke\"><a href=\"plugins/taobaoke/list.php?kind_id=%d\">淘宝客</a></li><li id=\"taobaoke\"><a href=\"plugins/taobaoke/checkitems.php?kind_id=%d\">检查下架商品</a></li>",$kind_id,$kind_id);}
echo ("</ul>");

echo ("loftCMS -> 文章管理 -> ".$page_title);
echo ("</div>\n\n");



//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:50px;">排序</th>');
echo('<th>标题</th>');
echo('<th style="width:140px;">发表时间</th>');
echo('<th style="width:80px;">总人气</th>');
echo('<th style="width:80px;">日均人气</th>');
echo('<th style="width:50px;">编辑ID</th>');
echo('<th style="width:40px;">状态</th>');
echo('<th style="width:40px;">修改</th>');
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
$icon_show = $is_show?"<span class=\"yes\">√</span>":"<span class=\"no\">×</span>";

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
printf('<td><a href="posts_edit.php?id=%d&u=%s" class="icon_edit">修改</a></td>',$id,$current_url);
echo('</tr>');
}
echo('</table>');

$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
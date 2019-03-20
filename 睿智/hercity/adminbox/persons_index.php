<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//获取类目id
$cat = _GET('cat','i');

//获取搜索条件
$search_field = _GET('search_field');
$search_key = _GET('search_key');

//断断是搜索还是按栏目列出。
if ($cat)
{
$str_where =  " where cat=".$cat ;
$page_title = "文章列表" ;
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
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."persons".$str_where)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 人物管理 -> ".$config['personCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"persons_new.php?cat=%d\">新建</a></li>",$cat);
echo ("<li id=\"search\"><a href=\"persons_search.php\">搜索</a>");
echo ("<li><a id=\"persons_show\" class=\"todo\">发布</a></li>");
echo ("<li><a id=\"persons_not_show\" class=\"todo\">取消发布</a></li>");
echo ("<li><a id=\"persons_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");





//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:150px;">姓名</th>');
echo('<th style="width:150px;">英文名</th>');
echo('<th style="width:60px;">人气</th>');
echo('<th style="width:60px;">粉丝</th>');
echo('<th style="width:60px;">百度指数</th>');
echo('<th style="text-align:left;">标题</th>');
echo('<th style="width:40px;">状态</th>');
echo('<th style="width:40px;">修改</th>');
echo('</tr>');


$sql = "select id,name,ename,hits,fans,baidu_index,title,is_show from ".$config['tablePre']."persons".$str_where." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while(list($id,$name,$ename,$hits,$fans,$baidu_index,$title,$is_show) = $result->fetch_row())
{
$icon_show = $is_show?"<span class=\"yes\">√</span>":"<span class=\"no\">×</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%s <a href="http://www.baidu.com/s?wd=%s" target="_blank">百度</a></td>',$name,$name);
printf('<td>%s</td>',$ename);
printf('<td>%s</td>',$hits);
printf('<td>%d</td>',$fans);
printf('<td>%d</td>',$baidu_index);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="persons_edit.php?id=%d" class="icon_edit">修改</a></td>',$id);
echo('</tr>');
}
echo('</table>');



$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
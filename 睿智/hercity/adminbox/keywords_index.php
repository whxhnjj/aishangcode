<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//获取搜索条件
$search_field = _GET('search_field');
$search_key = _GET('search_key');


$str_where = " where 1=1 ";
$page_title = "全部";

if ($search_key != '')
{
$str_where = " where ".$search_field." like '%".$search_key."%' " ;
$page_title = "搜索“".$search_key."”的结果" ;
}


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."keywords ".$str_where)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 关键词管理 ");

echo ("<ul id=\"tools\">");
echo ("<li><a href=\"keywords_search.php\">搜索</a></li>");
echo ("<li><a href=\"keywords_new.php\">新建</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:100px;">拼音</th>');
echo('<th>标签名</th>');
echo('<th style="width:80px;">引用次数</th>');
echo('<th style="width:80px;">修改</th>');
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
printf('<td><a href="keywords_edit.php?id=%d" class="icon_edit">修改</a></td>',$id);
echo('</tr>');
}
echo('</table>');







$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
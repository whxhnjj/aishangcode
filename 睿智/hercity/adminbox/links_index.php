<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


$cat = _GET('cat','i');

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."links where cat=".$cat)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 链接管理 -> ".$config['linkCats'][$cat]);

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"links_new.php?cat=%d\">新建</a></li>",$cat);
echo ("<li><a id=\"links_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");







//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:50px;">ID</th>');
echo('<th style="width:80px;">排序权值</th>');
echo('<th style="width:180px;text-align:left;">标题</th>');
echo('<th style="text-align:left;">链接</th>');
echo('<th style="width:80px;">人气</th>');
echo('<th style="width:40px;">修改</th>');
echo('</tr>');

$sql = "select id,title,linkto,order_id,hits from ".$config['tablePre']."links where cat=".$cat." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql);

$rowmum=1;
while(list($id,$title,$linkto,$order_id,$hits) = $result->fetch_row())
{
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$order_id);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td style="text-align:left;"><a href="%s">%s</a></td>',$linkto,substr($linkto,0,50));
printf('<td>%d</td>',$hits);
printf('<td><a href="links_edit.php?id=%d" class="icon_edit">修改</a></td>',$id);
echo('</tr>');
}
echo('</table>');






$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
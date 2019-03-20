<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//获取主题id
$subject_id = _GET('subject_id','i');

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."poll_options where subject_id=".$subject_id)->fetch_row();

//引导栏
echo ("<div id=\"path\">");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"poll_options_new.php?subject_id=%d\">新建</a></li>",$subject_id);
echo ("<li><a id=\"poll_options_show\" class=\"todo\">发布</a></li>");
echo ("<li><a id=\"poll_options_not_show\" class=\"todo\">取消发布</a></li>");
echo ("<li><a id=\"poll_options_del\" class=\"todo\">删除</a></li>");
echo ("<li><a href=\"poll_subjects_index.php\" >返回投票管理</a></li>");
echo ("</ul>");
echo ("loftCMS -> 投票管理 -> 备选项管理");
echo ("</div>\n\n");




//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:80px;">编号</th>');
echo('<th>标题</th>');
echo('<th style="width:140px;">得票数</th>');
echo('<th style="width:40px;">状态</th>');
echo('<th style="width:40px;">修改</th>');
echo('</tr>');


$sql = "select id,number,title,votes,is_show from ".$config['tablePre']."poll_options where subject_id=".$subject_id." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$number,$title,$votes,$is_show) = $result->fetch_row())
{

$icon_show = $is_show?"<span class=\"yes\">√</span>":"<span class=\"no\">×</span>";
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>NO.%d</td>',$number);
printf('<td style="text-align:left;">%s</td>',$title);
printf('<td>%s</td>',$votes);
printf('<td>%s</td>',$icon_show);
printf('<td><a href="poll_options_edit.php?id=%d" class="icon_edit">修改</a></td>',$id);
echo('</tr>');
}
echo('</table>');


$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
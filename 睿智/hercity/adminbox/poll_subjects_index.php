<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."poll_subjects")->fetch_row();

//引导栏
echo ("<div id=\"path\">");

echo ("<ul id=\"tools\">");
echo ("<li><a href=\"poll_subjects_new.php\">新建</a></li>");
echo ("<li><a id=\"poll_subjects_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("loftCMS -> 投票管理 -> 投票主题");

echo ("</div>\n\n");









//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:90px;">关联文章ID</th>');
echo('<th style="text-align:left;">投票主题</th>');
echo('<th style="width:140px;">到期时间</th>');
echo('<th style="width:80px;">修改</th>');
echo('<th style="width:80px;">管理备选项</th>');
echo('</tr>');


$sql = "select id,post_id,title,expiration from ".$config['tablePre']."poll_subjects order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$post_id,$title,$expiration) = $result->fetch_row())
{
$expiration=$expiration?date("Y-m-d H:i:s",$expiration):"不限";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$post_id);
printf('<td style="text-align:left;"><a href="poll_options_index.php?subject_id=%d">%s</a></td>',$id,$title);
printf('<td>%s</td>',$expiration);
printf('<td><a href="poll_subjects_edit.php?id=%d">修改</a></td>',$id);
printf('<td><a href="poll_options_index.php?subject_id=%d">管理备选项</a></td>',$id);
echo('</tr>');
}
echo('</table>');



$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
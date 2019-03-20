<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//确定是搜索还是全部列出
$key = _GET('key','s');
$str_where = $key?" where content like '%".$key."%' ":"" ;
$page_title = $key?"搜索“".$key."”的结果":"评论列表" ;

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."comments ".$str_where)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 评论管理 -> ".$page_title);

echo ("<ul id=\"tools\">");
echo ("<li><a id=\"comments_show\" class=\"todo\">发布</a></li>");
echo ("<li><a id=\"comments_not_show\" class=\"todo\">取消发布</a></li>");
echo ("<li><a id=\"comments_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:50px;">会员ID</th>');
echo('<th style="width:140px;">姓名</th>');
echo('<th>内容</th>');
echo('<th style="width:140px;">时间</th>');
echo('<th style="width:80px;">文章ID</th>');
echo('<th style="width:80px;">状态</th>');
echo('</tr>');


$sql = "select id,uid,name,content,dateline,post_id,is_show,email from ".$config['tablePre']."comments ".$str_where." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$uid,$name,$content,$dateline,$post_id,$is_show,$email) = $result->fetch_row())
{
$content = str_replace('<br>',' / ',$content);
$content = str_replace('<br />',' / ',$content);
$content = mb_substr($content,0,50,'gbk')."…";

$icon_show = $is_show ? "<span class=\"yes\">√</span>" : "<span class=\"no\">×</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%d</td>',$uid);
printf('<td>%s</td>',$name);
printf('<td style="text-align:left;">%s</td>',$content);
printf('<td>%s</td>',date("Y-m-d h:i",$dateline));
printf('<td><a href="%s?id=%d" target="_blank">%d</a></td>',$config['basePath'].$config['phpShowPath'],$post_id,$post_id);
printf('<td>%s</td>',$icon_show);
echo('</tr>');
}
echo('</table>');



$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
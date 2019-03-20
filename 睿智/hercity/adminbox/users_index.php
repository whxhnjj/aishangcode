<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//获取用户组
$usergroup = _GET('usergroup','i');

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize=20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."users where usergroup=".$usergroup)->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 用户管理 -> 用户列表");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"users_new.php?usergroup=%d\">新建</a></li>",$usergroup);
echo ("<li><a id=\"users_active\" class=\"todo\">激活</a></li>");
echo ("<li><a id=\"users_not_active\" class=\"todo\">冻结</a></li>");
echo ("<li><a id=\"users_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");







//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th>用户名</th>');
echo('<th style="width:80px;">昵称</th>');
echo('<th style="width:200px;">注册时间</th>');
echo('<th style="width:80px;">登录次数</th>');
echo('<th style="width:200px;">推广积分</th>');
echo('<th style="width:80px;">状态</th>');
echo('<th style="width:80px;">修改</th>');
echo('</tr>');


$sql = "select id,uname,nickname,dateline,logintimes,is_active,point from ".$config['tablePre']."users where usergroup=".$usergroup." order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while($rs = $result->fetch_array())
{
$icon_active = $rs['is_active']?"<span class=\"yes\">√</span>":"<span class=\"no\">×</span>";

echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$rs['id']);
printf('<td>%d</td>',$rs['id']);
printf('<td>%s</td>',$rs['uname']);
printf('<td>%s</td>',$rs['nickname']);
printf('<td>%s</td>',date("Y-m-d H:i:s",$rs['dateline']));
printf('<td>%d</td>',$rs['logintimes']);
printf('<td>%d</td>',$rs['point']);
printf('<td>%s</td>',$icon_active);
printf('<td><a href="users_edit.php?id=%d" class="icon_edit">修改</a></td>',$rs['id']);
echo('</tr>');
}
echo('</table>');




$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
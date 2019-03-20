<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$uid = $_SESSION['userid'];
$uname = $_SESSION['username'];

//用户信息
$sql = "select logintimes,point from ".$config['tablePre']."users where id = ".$uid;
$result = $mysqli->query($sql);
list($logintimes,$point) = $result->fetch_row();

//取今天编辑量
$sql = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where is_show and user_id = ".$uid." and DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y-%m-%d') = CURDATE()";
$result = $mysqli->query($sql);
list($posts_today,$hits_today,$comments_today) = $result->fetch_row();

//取30天编辑量
$sql = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where is_show and user_id = ".$uid." and dateline > UNIX_TIMESTAMP()-2679120";
$result = $mysqli->query($sql);
list($posts_30days,$hits_30days,$comments_30days) = $result->fetch_row();

//取数据库尺寸
$result = $mysqli->query("SHOW TABLE STATUS FROM ".$config['dbName']);
$database_size = "";
while($rs = $result->fetch_row())
{
$database_size += $rs[6]+$rs[8];
}



printf("<h2>%s 管理中心</h2>",$config['siteName']);

printf("<h4>工作提示</h4>");
printf("<span class=\"red\">%s</span>(UID:%d)，您好！<br />",$uname,$uid);
printf("这是您第<span class=\"orange\">(%d)</span>次登录，您累积完成<span class=\"orange\">(%d)</span>个推广积分。<br />",$logintimes,$point);
printf("您今天共发布文章<span class=\"orange\">(%d)</span>篇，这些文章目前共被点击<span class=\"orange\">(%d)</span>人次，共被评论<span class=\"orange\">(%d)</span>人次。<br />",$posts_today,$hits_today,$comments_today);
printf("您30天内发布文章<span class=\"orange\">(%d)</span>篇，这些文章目前共被点击<span class=\"orange\">(%d)</span>人次，共被评论<span class=\"orange\">(%d)</span>人次。",$posts_30days,$hits_30days,$comments_30days);

printf("<h4>系统信息</h4>");
printf("当前软件版本：%s<br />",$config['softVersion']);
printf("软件最后更新：%s<br />",$config['softUpdate']);
printf("当前数据库尺寸：%s M<br />",round($database_size/1024/1024,2));

printf("<h4>时间信息</h4>");
printf("本次登录时间：%s<br />",date('Y-m-d H:i:s',$_SESSION['userlogintime']));



require_once("includes/footer.php");
?>
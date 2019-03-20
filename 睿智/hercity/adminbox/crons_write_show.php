<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

set_time_limit(120);


//本批处理的起始点
$i = _GET('i','i',0);

//每批写的文件数
$writecount = $config['htmlShowCount'];

//读取总记录数
if (isset($_SESSION['recoudcount']))
{
	$recoudcount = $_SESSION['recoudcount'];
}
else
{
	list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where special=0")->fetch_row();
	$_SESSION['recoudcount'] = $recoudcount;
}

$sql = "select id,title,dateline from ".$config['tablePre']."posts where special=0 and is_show order by id desc limit $i,$writecount";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$i_start = $i+1;
$i_end = (($i+$writecount)>$recoudcount)?$recoudcount:($i+$writecount);
$progress = (ceil($i_start/$recoudcount*100))."%";
printf("共%d条记录，当前正在处理第%d到%d条...(%s)<hr />",$recoudcount,$i_start,$i_end,$progress);

while(list($id,$title,$dateline) = $result->fetch_row())
{
	//调用写入单文章函数
	$writeShowHtml = writeShowHtml($id,$dateline);
	if ($writeShowHtml>0) printf ("%d.%s<br />",$id,$title);
}
$result->free();

$i += $writecount;

if ($i < $recoudcount)
{
printf("<hr />请等待，页面将自动跳转。若不能自动跳转请<a href=\"%s?i=%d\">点击这里</a>",$_SERVER['PHP_SELF'],$i);
printf("<script type=\"text/javascript\">window.location=\"%s?i=%d\";</script>",$_SERVER['PHP_SELF'],$i);
}
else
{
echo("全部处理完成!<a href=\"crons_write_index.php\">返回</a>");
}

require_once("includes/footer.php");
?>
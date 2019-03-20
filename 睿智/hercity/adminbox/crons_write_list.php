<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//本批处理的起始点，默认从第1页开始。
$i = _GET('i','i',1);

//每批写的文件数
$writecount = $config['htmlListCount'];

//当前处理的栏目
$kind_id = _GET('kind_id','i');
if (!$kind_id) list($kind_id) = $mysqli->query("select id from ".$config['tablePre']."kinds where is_list and folder<>'' order by id limit 1")->fetch_row();

//取得栏目对应的html页保存路径与每页记录数
list($folder,$pagesize,$name) = $mysqli->query("select folder,pagesize,name from ".$config['tablePre']."kinds where id=".$kind_id)->fetch_row();

//确定总记录数，并算出总页数。
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren($kind_id).")")->fetch_row();
$pagecount = ceil($recoudcount/$pagesize);
if ($pagecount == 0) $pagecount = 1;

//本次输出的开始页与结束页
$i_start = $i;
$i_end = ($pagecount<=$i+$writecount)?$pagecount:($i+$writecount-1);

printf("当前栏目：%s。<br />",$name);
printf("共%d页。正在处理第%d-%d页。<br />",$pagecount,$i_start,$i_end);

for ($i=$i_start;$i<=$i_end;$i++)
{
	$writeListHtml = writeListHtml($kind_id,$folder,$i);
	echo ($writeListHtml);
}

echo "<hr />";
//sleep(1);


if ($pagecount>=$i)
{
	printf("<script type=\"text/javascript\">window.location=\"%s?kind_id=%d&i=%d\";</script>",$_SERVER['PHP_SELF'],$kind_id,$i);
}
else
{
	list($kind_id) = $mysqli->query("select id from ".$config['tablePre']."kinds where id>".$kind_id." and is_list and folder<>'' order by id limit 1")->fetch_row();
	$i = 1;
	if ($kind_id)
	printf("<script type=\"text/javascript\">window.location=\"%s?kind_id=%d&i=%d\";</script>",$_SERVER['PHP_SELF'],$kind_id,$i);
	else
	echo "全部处理完成!<a href=\"crons_write_index.php\">返回</a>";
}

require_once("includes/footer.php");
?>
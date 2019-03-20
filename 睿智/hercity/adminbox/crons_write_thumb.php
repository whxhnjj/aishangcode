<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

set_time_limit(60);

//批量更新附件图片的缩略图。

$i = _GET('i','i',0);
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."attachments")->fetch_row();


$sql = "select filename from ".$config['tablePre']."attachments order by id desc limit ".$i.",10 ";
$result = $mysqli->query($sql);


$documentRoot = $_SERVER['DOCUMENT_ROOT'];
while(list($filename) = $result->fetch_row())
{
	$i++;
	$targetRootFile = $documentRoot."/".$config['attachmentPath'].$filename;
	if (file_exists($targetRootFile))
	{
		echo $targetRootFile.".OK.<br />";

		//生成缩略图
		$tb=new thumb();
		$tb->SetVar($targetRootFile,"file");
		$tb->Prorate($targetRootFile.'.thumb.jpg',$config['attachmentThumbWidth'],$config['attachmentThumbHeight']);
	}
}


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
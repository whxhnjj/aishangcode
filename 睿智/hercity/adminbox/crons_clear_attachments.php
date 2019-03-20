<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$sql="select filename from ".$config['tablePre']."attachments where post_id = 0";
$result = $mysqli->query($sql);
while(list($filename) = $result->fetch_row())
{
	if ($filename)
	{
	$documentRoot = $_SERVER['DOCUMENT_ROOT'];
	$targetPath = $config['basePath'].$config['attachmentPath'];
	$targetfile = $documentRoot.$targetPath.$filename;
	if (file_exists($targetfile)) @unlink($targetfile);
	if (file_exists($targetfile.'.thumb.jpg')) @unlink($targetfile.'.thumb.jpg');
	}
}

$sql="delete from ".$config['tablePre']."attachments where post_id = 0";
$result = $mysqli->query($sql);

echo ("全部处理完成!<a href=\"crons_clear_index.php\">返回</a>");

require_once("includes/footer.php");
?>
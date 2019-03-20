<?php
//建立数据库连接实例$mysqli，若未开启mysqli扩展，则用自定义的mesqli类。
if (@extension_loaded('mysqli'))
{
	@$mysqli = new mysqli();
}
else
{
	@$mysqli = new mesqli();
}
@$mysqli->connect($config['dbHost'],$config['dbUser'],$config['dbPwd']);
$mysqli->query("set names 'gbk'");
$mysqli->select_db($config['dbName']);

if ($mysqli->errno)
{
printf("连接数据库错误:%s",$mysqli->error);
exit();
}


//每日首次访问时更新全站html页面
if ($config['autoWriteHtml'])
{
	$targetFile = $_SERVER['DOCUMENT_ROOT'].$config['basePath']."data/last_auto_write_html.txt";

	if (file_exists($targetFile))
	{
	$lastWriteDate = file_get_contents($targetFile);
	if (date("Ymd",$lastWriteDate)<date("Ymd")) require_once("crons_auto_write_all.php");
	}
	else
	{
	require_once("crons_auto_write_all.php");
	}
}
?>
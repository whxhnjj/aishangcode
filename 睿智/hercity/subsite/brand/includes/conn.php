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
?>
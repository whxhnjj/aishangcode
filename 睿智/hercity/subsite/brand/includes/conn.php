<?php
//�������ݿ�����ʵ��$mysqli����δ����mysqli��չ�������Զ����mesqli�ࡣ
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
printf("�������ݿ����:%s",$mysqli->error);
exit();
}
?>
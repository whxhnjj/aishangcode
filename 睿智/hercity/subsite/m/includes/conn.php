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


//ÿ���״η���ʱ����ȫվhtmlҳ��
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
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$name = _POST('name');
$ename = _POST('ename');
$title = _POST('title');
$description = _POST('description');
$linkto = _POST('linkto');


//����ͬ���ؼ��ʣ�����еĻ���
$sql = "select name,ename from ".$config['tablePre']."keywords where (name='".$name."' or ename = '".$ename."')";

$result = $mysqli->query($sql);

if ($rs = $result->fetch_row())
{
	echo '��ǩ����Ӣ�����ظ�';
	exit;
}


$sql = "insert into ".$config['tablePre']."keywords (name,ename,title,description,linkto,hits) values ('".$name."','".$ename."','".$title."','".$description."','".$linkto."',0)";


$result = $mysqli->query($sql);


$url=$_POST["url"];
redirect("�ؼ����½��ɹ�",$url);
require_once("includes/footer.php");
?>
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


//处理同名关键词，如果有的话。
$sql = "select name,ename from ".$config['tablePre']."keywords where (name='".$name."' or ename = '".$ename."')";

$result = $mysqli->query($sql);

if ($rs = $result->fetch_row())
{
	echo '标签名或英文名重复';
	exit;
}


$sql = "insert into ".$config['tablePre']."keywords (name,ename,title,description,linkto,hits) values ('".$name."','".$ename."','".$title."','".$description."','".$linkto."',0)";


$result = $mysqli->query($sql);


$url=$_POST["url"];
redirect("关键词新建成功",$url);
require_once("includes/footer.php");
?>
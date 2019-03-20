<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$old_name = _POST('old_name');
$hits = _POST('hits','i');
$name = _POST('name');
$ename = _POST('ename');
$title = _POST('title');
$description = _POST('description');
$linkto = _POST('linkto');


//处理同名关键词，如果有的话。
$sql = "select name,ename from ".$config['tablePre']."keywords where (name='".$name."' or ename = '".$ename."') and id<>".$id;
$result = $mysqli->query($sql);

if ($rs = $result->fetch_row())
{
	echo '标签名或英文名重复';
	exit;	
}





if ($name != $old_name)
{
	//更新文章中的关键词
	$sql = "select id,keyword from ".$config['tablePre']."posts where instr(keyword,'".$old_name."') > 0";
	$result = $mysqli->query($sql);
	while(list($post_id,$keyword) = $result->fetch_row())
	{
	$keyword = str_replace(",".$old_name."," , ",".$name."," , ",".$keyword.",");
	$keyword = trim($keyword,",");
	$sql = "update ".$config['tablePre']."posts set keyword = '".$keyword."' where id = ".$post_id;
	$mysqli->query($sql);
	}
}



$sql = "update ".$config['tablePre']."keywords set name='".$name."',ename='".$ename."',title='".$title."',description='".$description."',linkto='".$linkto."' where id=".$id;
$result = $mysqli->query($sql);


$url=$_POST["url"];
redirect("关键词修改成功",$url);
require_once("includes/footer.php");
?>
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


//����ͬ���ؼ��ʣ�����еĻ���
$sql = "select name,ename from ".$config['tablePre']."keywords where (name='".$name."' or ename = '".$ename."') and id<>".$id;
$result = $mysqli->query($sql);

if ($rs = $result->fetch_row())
{
	echo '��ǩ����Ӣ�����ظ�';
	exit;	
}





if ($name != $old_name)
{
	//���������еĹؼ���
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
redirect("�ؼ����޸ĳɹ�",$url);
require_once("includes/footer.php");
?>
<?php
require_once("../includes/config.php");
require_once("../includes/rights.php");
require_once("../includes/function.php");
require_once("../includes/conn.php");

$name = iconv("UTF-8","gb2312",$_POST['name']);
$ename = _POST('ename');
$id = _POST('id','i',0);
$j = 0;

if ($name)
{
	$sql = "select count(id) from ".$config['tablePre']."persons where name = '".$name."' and id <> ".$id;
	list($i) = $mysqli->query($sql)->fetch_row();
	if ($i){$j += 1;}
}

if ($ename)
{
	$sql = "select count(id) from ".$config['tablePre']."persons where ename = '".$ename."' and id <> ".$id;
	list($i) = $mysqli->query($sql)->fetch_row();
	if ($i){$j += 2;}
}

echo $j;
?>
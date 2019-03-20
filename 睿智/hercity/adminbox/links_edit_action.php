<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$cat = _POST('cat');
$title = _POST('title');
$linkto = _POST('linkto','t');
$thumb1 = _POST('thumb1');
$order_id = _POST('order_id','i');

$sql = "update ".$config['tablePre']."links set cat=".$cat.",title='".$title."',linkto='".$linkto."',thumb1='".$thumb1."',order_id=".$order_id." where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("链接修改成功",$url);
require_once("includes/footer.php");
?>
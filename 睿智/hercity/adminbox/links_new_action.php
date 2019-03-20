<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$cat = _POST('cat','i');
$title = _POST('title');
$linkto = _POST('linkto','t');
$thumb1 = _POST('thumb1');
$order_id = _POST('order_id','i');

$sql = "insert into ".$config['tablePre']."links (cat,title,linkto,thumb1,order_id) values (".$cat.",'".$title."','".$linkto."','".$thumb1."',".$order_id.")";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("链接新建成功",$url);
require_once("includes/footer.php");
?>
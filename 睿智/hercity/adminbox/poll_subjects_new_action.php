<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$post_id = _POST('post_id');
$title = _POST('title');
$content = _POST('content');
$y = _POST('y','i');
$m = _POST('m','i');
$d = _POST('d','i');
$h = _POST('h','i');
$i = _POST('i','i');
$s = _POST('s','i');

$expiration = ($y+$m+$d+$h+$i+$s)?mktime($h,$i,$s,$m,$d,$y):0;


$sql = "insert into ".$config['tablePre']."poll_subjects (post_id,title,content,expiration) values (".$post_id.",'".$title."','".$content."',".$expiration.")";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("投票主题新建成功",$url);
require_once("includes/footer.php");
?>
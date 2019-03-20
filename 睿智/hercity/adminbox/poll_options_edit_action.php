<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$number = _POST('number','i');
$title = _POST('title');
$votes = _POST('votes','i');
$linkto = _POST('linkto','t');
$content = _POST('content');
$info = _POST('info');
$thumb1 = _POST('thumb1');
$thumb2 = _POST('thumb2');

$sql = "update ".$config['tablePre']."poll_options set number=".$number.",title='".$title."',votes=".$votes.",linkto='".$linkto."',content='".$content."',info='".$info."',thumb1='".$thumb1."',thumb2='".$thumb2."' where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("投票备选项修改成功",$url);
require_once("includes/footer.php");
?>
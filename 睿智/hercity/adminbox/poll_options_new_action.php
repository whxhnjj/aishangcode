<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$subject_id = _POST('subject_id','i');
$number = _POST('number','i');
$title = _POST('title');
$votes = _POST('votes','i');
$linkto = _POST('linkto','t');
$content = _POST('content');
$info = _POST('info');
$thumb1 = _POST('thumb1');
$thumb2 = _POST('thumb2');
$is_show = 1;

$sql = "insert into ".$config['tablePre']."poll_options (subject_id,number,title,votes,linkto,content,info,thumb1,thumb2,is_show) values (".$subject_id.",".$number.",'".$title."',".$votes.",'".$linkto."','".$content."','".$info."','".$thumb1."','".$thumb2."',".$is_show.")";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("投票备选项新建成功",$url);
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST("id",'i');
$title = _POST("title");
$description = _POST("description");

$sql = "update ".$config['tablePre']."attachments set title='".$title."',description='".$description."' where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("附件修改成功",$url);
require_once("includes/footer.php");
?>
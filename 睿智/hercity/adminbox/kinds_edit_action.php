<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$parent_id = _POST('parent_id','i');
$name = _POST('name');
$title = _POST('title');
$keywords = _POST('keywords');
$keywords = formatKeywords($keywords);
$description = _POST('description');
$is_list = _POST('is_list','i');
$folder = _POST('folder');
$pagesize = _POST('pagesize','i');
$template = _POST('template');
$showtemplate = _POST('showtemplate');
$remark = _POST('remark');
$linkto = _POST('linkto','t');



$sql = "update ".$config['tablePre']."kinds set parent_id='".$parent_id."',name='".$name."',title='".$title."',keywords='".$keywords."',description='".$description."',is_list=".$is_list.",folder='".$folder."',pagesize=".$pagesize.",template='".$template."',showtemplate='".$showtemplate."',remark='".$remark."',linkto='".$linkto."' where id=".$id;

$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("栏目修改成功",$url.'#k'.$id);
require_once("includes/footer.php");
?>
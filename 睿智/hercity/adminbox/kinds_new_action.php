<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

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

$sql = "insert into ".$config['tablePre']."kinds (parent_id,name,title,keywords,description,is_list,folder,pagesize,template,showtemplate,remark,linkto) values (".$parent_id.",'".$name."','".$title."','".$keywords."','".$description."',".$is_list.",'".$folder."',".$pagesize.",'".$template."','".$showtemplate."','".$remark."','".$linkto."')";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("栏目新建成功",$url);
require_once("includes/footer.php");
?>
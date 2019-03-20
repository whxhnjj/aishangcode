<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$cat = _POST('cat','i');
$theletter = _POST('theletter');
$tags = isset($_POST['tags']) ? implode(',',_POST('tags','as')) : '';
$name = _POST('name');
$oname = _POST('oname');
$oname = formatKeywords($oname);
$ename = _POST('ename');
$gender = _POST('gender');
$profile = _POST('profile');
$archives = _POST('archives');
$archives = str_replace(':','：',$archives);
$website = _POST('website');
$weibo = _POST('weibo');
$thumb1 = _POST('thumb1');
$thumb2 = _POST('thumb2');
$title = _POST('title');
$keywords = _POST('keywords');
$keywords = formatKeywords($keywords);
$description = _POST('description');
$is_show = _POST('is_show','i',1);


$sql = "update ".$config['tablePre']."persons set cat=".$cat.",theletter='".$theletter."',tags='".$tags."',name='".$name."',oname='".$oname."',ename='".$ename."',gender=".$gender.",profile='".$profile."',archives='".$archives."',website='".$website."',weibo='".$weibo."',thumb1='".$thumb1."',thumb2='".$thumb2."',title='".$title."',keywords='".$keywords."',description='".$description."',is_show=".$is_show." where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("人物修改成功",$url);
require_once("includes/footer.php");
?>
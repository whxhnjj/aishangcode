<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$i = _GET('i','s','new');
switch ($i)
{
case "new":
	$orderby = "id";
	$title = "最新发表TOP100";
	break;
case "hits":
	$orderby = "hits";
	$title = "最高人气TOP100";
	break;
case "comments":
	$orderby = "comments";
	$title = "最热评论TOP100";
	break;
}

$location = '<a href="/">首页</a>  &gt; '.$title;

//文章列表
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and special=0 order by ".$orderby." desc limit 100",44);

//右侧文章
$list_hot = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." and thumb1<>'' order by hits desc limit 10");

$template = "top100.html";
$smarty->assign("title",$title);
$smarty->assign("location",$location);
$smarty->assign("list",$list);
$smarty->assign("list_hot",$list_hot);
$smarty->display($template);

$mysqli->close();
?>
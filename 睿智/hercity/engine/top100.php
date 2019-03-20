<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$i = _GET('i','s','new');
switch ($i)
{
case "new":
	$orderby = "id";
	$title = "���·���TOP100";
	break;
case "hits":
	$orderby = "hits";
	$title = "�������TOP100";
	break;
case "comments":
	$orderby = "comments";
	$title = "��������TOP100";
	break;
}

$location = '<a href="/">��ҳ</a>  &gt; '.$title;

//�����б�
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and special=0 order by ".$orderby." desc limit 100",44);

//�Ҳ�����
$list_hot = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." and thumb1<>'' order by hits desc limit 10");

$template = "top100.html";
$smarty->assign("title",$title);
$smarty->assign("location",$location);
$smarty->assign("list",$list);
$smarty->assign("list_hot",$list_hot);
$smarty->display($template);

$mysqli->close();
?>
<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$i = _GET('i','s','new');
switch ($i)
{
case "new":
	$orderby = "id";
	$kind_name = "����Ӱ��";
	break;
case "hits":
	$orderby = "hits";
	$kind_name = "����Ӱ��";
	break;
case "comments":
	$orderby = "comments";
	$kind_name = "����Ӱ��";
	break;
}

//�����б�
$list = getArrayFromPosts("where kind_id in (24,25,26,27,53) and is_show and dateline<=UNIX_TIMESTAMP() order by ".$orderby." desc limit 20");

$template = "list_photo.html";
$smarty->assign("title",$kind_name);
$smarty->assign("location",sprintf("<a href=\"%s\">��ҳ</a> &gt; ",$config['basePath']).$kind_name);
$smarty->assign("kind_folder",$orderby);
$smarty->assign("pages",'');
$smarty->assign("list",$list);
$smarty->display($template);

$mysqli->close();
?>
<?php
require_once('ini.php');

$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$pagesize = 20;
$kind_id = (isset($_GET['kind_id'])) ? $_GET['kind_id'] : 24;

if ($kind_id == 55){
	$list = getPostList($pagesize,$page,$kind_id,9);//淘客栏目，可以列出链接类文章。
}
else{
	$list = getPostList($pagesize,$page,$kind_id);
}

$smarty->assign("kind_id",$kind_id);
$smarty->assign("list",$list);
$smarty->assign("page",$page);
$smarty->display($list['kind']['template']);
?>
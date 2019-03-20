<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page',"i",1);
$pagesize = 200;

list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."keywords ")->fetch_row();
$page_max_no = $recordcount - ($pagesize * ($page - 1));

//栏目文章列表
$list = getArrayFromKeywords("order by hits desc limit ".($page-1)*$pagesize.",".$pagesize);

//调用分页函数
$pages = pages($recordcount,$pagesize,$page,"%d.html");

//右侧文章
$list_hot = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 5");

$smarty->assign("list",$list);
$smarty->assign("pages",$pages);
$smarty->assign("list_hot",$list_hot);
$smarty->assign("page_max_no",$page_max_no);
$smarty->display("tags.html");

$mysqli->close();
?>
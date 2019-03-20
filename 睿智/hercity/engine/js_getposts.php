<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$i = _GET('i','i',4);

switch ($i)
{
case 1:
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (24,25,26,27) and thumb2<>'' order by rand() limit 4",34);
if (! $list)  $list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (24,25,26,27) order by id desc limit 4",34);
$smarty->assign("list",$list);
break;

case 2:
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (35) and thumb2<>'' order by rand() limit 5",34);
if (! $list)  $list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (35) order by id desc limit 5",34);
$smarty->assign("list",$list);
break;


//会馆广告
case 3:
$banners = getArrayFromLinks("where cat=17 and order_id>-9999 order by order_id desc,id desc limit 7");
$smarty->assign("banners",$banners);
break;

//会馆首页焦点文章
case 4:
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (33,34,35) and brief<>'' order by id desc limit 3",52,90);
$smarty->assign("list",$list);
break;

//会馆首页焦点图下方
case 5:
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (35) order by id desc limit 3",44);
$smarty->assign("list",$list);
break;

//文章页点击排行
case 6:
$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 10",40);
$smarty->assign("list",$list);
break;

//会馆首页焦点图
case 7:
$pics = getArrayFromLinks("where cat=18 and order_id>-9999  order by rand() limit 6");
$smarty->assign("pics",$pics);
break;
}

$smarty->assign("i",$i);

//按指定模板输出
$template = "js_getposts.html";
$smarty->display($template);

$mysqli->close();
?>
<?php
require_once("inc_header.php");


$persons_top = getArrayFromPersons("where is_show and thumb2<>'' and cat<>5 order by baidu_index desc,id desc limit 20");
$persons_new = getArrayFromPersons("where is_show and thumb1<>'' order by id desc limit 64");

$persons_01 = getArrayFromPersons("where is_show and tags like '%��Ա%' or tags like '%����%' order by baidu_index desc,id desc limit 10");
$persons_02 = getArrayFromPersons("where is_show and tags like '%������Ů%' order by baidu_index desc,id desc limit 10");
$persons_03 = getArrayFromPersons("where is_show and tags like '%��̳��Ů%' order by baidu_index desc,id desc limit 10");
$persons_04 = getArrayFromPersons("where is_show and tags like '%У��%' order by baidu_index desc,id desc limit 10");
$persons_05 = getArrayFromPersons("where is_show and tags like '%����%' order by baidu_index desc,id desc limit 10");
$persons_06 = getArrayFromPersons("where is_show and tags like '%�����Ů%' order by baidu_index desc,id desc limit 10");

$news = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=104 order by order_id desc,id desc limit 8",36);
$links = getArrayFromLinks("where cat=302 and order_id>-9999 order by order_id desc,id");


$smarty->assign("persons_top",$persons_top);
$smarty->assign("persons_new",$persons_new);
$smarty->assign("persons_01",$persons_01);
$smarty->assign("persons_02",$persons_02);
$smarty->assign("persons_03",$persons_03);
$smarty->assign("persons_04",$persons_04);
$smarty->assign("persons_05",$persons_05);
$smarty->assign("persons_06",$persons_06);
$smarty->assign("news",$news);
$smarty->assign("links",$links);
$smarty->display("index.html");

$mysqli->close();
?>
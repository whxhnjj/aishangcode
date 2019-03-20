<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$links = getArrayFromLinks("where cat=99 and order_id>-9999 order by order_id desc,id");

$smarty->assign("ads",$ads);
$smarty->assign("links",$links);
$template = "static/links.html";
$smarty->display($template);
?>
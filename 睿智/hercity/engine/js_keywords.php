<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$i = _GET('i','i',0);
$count = _GET('count','i',80);

$list_keywords = getArrayFromKeywords("order by rand() limit ".$count);
$smarty->assign("i",$i);
$smarty->assign("list_keywords",$list_keywords);

//��ָ��ģ�����
$template = "js_keywords.html";
$smarty->display($template);

$mysqli->close();
?>
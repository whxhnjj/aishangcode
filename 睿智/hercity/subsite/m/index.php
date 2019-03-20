<?php
require_once('ini.php');

$page = 1;
$pagesize = 5;
$kind_id = 38;
$slides = getSlide($pagesize,$page,$kind_id);

$smarty->assign("slides",$slides);
$smarty->display('index.html');
?>
<?php
require_once('ini.php');

$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$pagesize = 20;
$list = getBrandList($pagesize,$page);

$smarty->assign("list",$list);
$smarty->assign("page",$page);
$smarty->display('brand_list.html');
?>
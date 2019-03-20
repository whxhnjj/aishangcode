<?php
require_once('ini.php');

$id = $_GET['id'];
$brand = getBrandDetail($id);


$smarty->assign("brand",$brand);
$smarty->display('brand_detail.html');
?>
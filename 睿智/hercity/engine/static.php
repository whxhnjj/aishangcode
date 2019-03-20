<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$file = $_GET['file'];

$smarty->assign("file",$file);
$template = "static/".$file.".html";
$smarty->display($template);
?>
<?php
require_once($config['smartyPath']."Smarty.class.php");

$smarty = new smarty;

$smarty->template_dir = "templates/";
$smarty->config_dir = "configs/";
$smarty->compile_dir = "../data/smarty_compile";
$smarty->cache_dir = "../data/smarty_cache";
$smarty->caching = false;
if ($loftcms!="") $smarty->assign("loftcms",$loftcms);
?>
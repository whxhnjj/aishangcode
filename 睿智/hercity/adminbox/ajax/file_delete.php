<?php
require_once("../includes/config.php");
require_once("../includes/rights.php");
require_once("../includes/function.php");


$filename = _POST('filename');
if ($filename)
{
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$targetPath = $config['basePath'].$config['thumbPath'];
$targetfile = $documentRoot.$targetPath.$filename;
if (file_exists($targetfile)) @unlink($targetfile);
echo("ok");
}

?>
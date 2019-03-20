<?php
require_once("../includes/interface.php");
require_once(dirname(__FILE__)."/../../".$config['adminPath']."libraries/phpqrcode/qrlib.php");

$url = _POST('url');

$PNG_TEMP_DIR = '../../data/temp/';
$PNG_WEB_DIR = '/data/temp/';
if (!file_exists($PNG_TEMP_DIR)){mkdirs($PNG_TEMP_DIR);}

$filename = $PNG_TEMP_DIR.date('His').rand(100,999).'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 8;
QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
echo $PNG_WEB_DIR.basename($filename);
?>
<?php
require_once("../includes/config.php");
require_once("../includes/rights.php");
require_once("../includes/function.php");

$error = "";
$msg = "";
$fileElementName = 'fileToUpload';
if(!empty($_FILES[$fileElementName]['error']))
{
	switch($_FILES[$fileElementName]['error'])
	{
		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;
			case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
{
	$error = 'No file was uploaded..';
}else 
{
	$tempFile = $_FILES[$fileElementName]['tmp_name'];
	$filesize = $_FILES[$fileElementName]['size'];
	$filetype = $_FILES[$fileElementName]['type'];
	$fileExt = "*".strrchr($_FILES[$fileElementName]['name'], ".");
	
	if (in_array($fileExt,$config['thumbAllowExt']) && in_array($filetype,$config['thumbAllowMime']) && $filesize < $config['thumbAllowSize'])
	{
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];
		$targetPath = $config['basePath'].$config['thumbPath'];
		$datePath = date('Y/m/');
		$targetPath = $targetPath.$datePath;
		if (!file_exists($documentRoot.$targetPath)) mkdirs($documentRoot.$targetPath);
		
		$newFileName = renameUploadFile( $_FILES[$fileElementName]['name']);
		
		$targetFile = $targetPath.$newFileName;
		$targetRootFile = str_replace('//','/',$documentRoot.$targetFile);

		move_uploaded_file($tempFile,$targetRootFile);
		$msg .= ($datePath.$newFileName);
	}
	else
	{
		$error = "文件太大或类型不正确.";
	}
}
	
echo "{";
echo "error: '" . $error . "',\n";
echo "msg: '" . $msg . "'\n";
echo "}";
?>
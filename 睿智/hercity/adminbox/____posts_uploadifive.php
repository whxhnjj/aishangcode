<?php
//此句是配合前端解决google和火狐浏览器无法上传的问题。
//if (array_key_exists('phpsession', $_REQUEST)) {session_id($_REQUEST['phpsession']);}

require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");


$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken){
	$file =  $_FILES['Filedata'];
	$file['ext'] = strtolower(strrchr($file['name'], "."));

	
	if (!is_uploaded_file($file['tmp_name'])){echo "err"; exit;} //安检
	if (!is_image($file['tmp_name'])){echo "err"; exit;} //安检


	//安检，对文件mime、扩展名、大小进行全面判断。
	if (in_array($file['type'],$config['attachmentAllowMime']) && in_array($file['ext'],$config['attachmentAllowExt']) && $file['size'] < $config['attachmentAllowSize'])
	{
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];		

		$uploadPath = $_POST['folder'];
		$targetPath = $uploadPath . date('Y/m/');
		if (!file_exists($documentRoot.$targetPath)) mkdirs($documentRoot.$targetPath);
		
		$newFileName = renameUploadFile( $_FILES['Filedata']['name']);
		$targetFile = $targetPath.$newFileName;
		$targetRootFile = str_replace('//','/',$documentRoot.$targetFile);

		
		move_uploaded_file($file['tmp_name'],$targetRootFile);
		//防止中文文件名乱码
		//move_uploaded_file($file['tmp_name'],iconv('utf-8','gbk', $targetFile));
		
		$filename = str_replace($uploadPath,"",$targetFile);
		$sql="insert into ".$config['tablePre']."attachments (post_id,title,description,filename,filesize,filetype,dateline,is_img) values (0,'','','".$filename."',".$file['size'].",'".$file['type']."',".time().",1)";
		$result = $mysqli->query($sql);
		
		//生成缩略图 需要GD库的支持。
		$tb=new thumb();
		$tb->SetVar($targetRootFile,"file");
		$tb->Prorate($targetRootFile.'.thumb.jpg',$config['attachmentThumbWidth'],$config['attachmentThumbHeight']);
		
		//返回文件相对地址
		echo $mysqli->insert_id.",".$targetFile;
	}
	else
	{
		echo "err";
	}
}




function is_image($tempFile) {
	// Get the size of the image
    $size = getimagesize($tempFile);
	if (isset($size) && $size[0] && $size[1] && $size[0] *  $size[1] > 0) {
		return true;
	} else {
		return false;
	}
}

?>
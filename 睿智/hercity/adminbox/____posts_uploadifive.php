<?php
//�˾������ǰ�˽��google�ͻ��������޷��ϴ������⡣
//if (array_key_exists('phpsession', $_REQUEST)) {session_id($_REQUEST['phpsession']);}

require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");


$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken){
	$file =  $_FILES['Filedata'];
	$file['ext'] = strtolower(strrchr($file['name'], "."));

	
	if (!is_uploaded_file($file['tmp_name'])){echo "err"; exit;} //����
	if (!is_image($file['tmp_name'])){echo "err"; exit;} //����


	//���죬���ļ�mime����չ������С����ȫ���жϡ�
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
		//��ֹ�����ļ�������
		//move_uploaded_file($file['tmp_name'],iconv('utf-8','gbk', $targetFile));
		
		$filename = str_replace($uploadPath,"",$targetFile);
		$sql="insert into ".$config['tablePre']."attachments (post_id,title,description,filename,filesize,filetype,dateline,is_img) values (0,'','','".$filename."',".$file['size'].",'".$file['type']."',".time().",1)";
		$result = $mysqli->query($sql);
		
		//��������ͼ ��ҪGD���֧�֡�
		$tb=new thumb();
		$tb->SetVar($targetRootFile,"file");
		$tb->Prorate($targetRootFile.'.thumb.jpg',$config['attachmentThumbWidth'],$config['attachmentThumbHeight']);
		
		//�����ļ���Ե�ַ
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
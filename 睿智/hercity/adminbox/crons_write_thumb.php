<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

set_time_limit(60);

//�������¸���ͼƬ������ͼ��

$i = _GET('i','i',0);
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."attachments")->fetch_row();


$sql = "select filename from ".$config['tablePre']."attachments order by id desc limit ".$i.",10 ";
$result = $mysqli->query($sql);


$documentRoot = $_SERVER['DOCUMENT_ROOT'];
while(list($filename) = $result->fetch_row())
{
	$i++;
	$targetRootFile = $documentRoot."/".$config['attachmentPath'].$filename;
	if (file_exists($targetRootFile))
	{
		echo $targetRootFile.".OK.<br />";

		//��������ͼ
		$tb=new thumb();
		$tb->SetVar($targetRootFile,"file");
		$tb->Prorate($targetRootFile.'.thumb.jpg',$config['attachmentThumbWidth'],$config['attachmentThumbHeight']);
	}
}


if ($i < $recoudcount)
{
printf("<hr />��ȴ���ҳ�潫�Զ���ת���������Զ���ת��<a href=\"%s?i=%d\">�������</a>",$_SERVER['PHP_SELF'],$i);
printf("<script type=\"text/javascript\">window.location=\"%s?i=%d\";</script>",$_SERVER['PHP_SELF'],$i);
}
else
{
echo("ȫ���������!<a href=\"crons_write_index.php\">����</a>");
}

require_once("includes/footer.php");
?>
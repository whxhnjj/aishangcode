<?php
ignore_user_abort(); //��ʹClient�Ͽ�(��ص������)��PHP�ű�Ҳ���Լ���ִ��.
set_time_limit(0); // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ

//д��ʱ�����ļ�
$targetPath = $_SERVER['DOCUMENT_ROOT'].$config['basePath']."data/";
$file=fopen($targetPath."last_auto_write_html.txt","w+");
fwrite($file,time());
fclose($file);

//д����ҳ
$sql = "select id,title,dateline from ".$config['tablePre']."posts order by id desc";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

while(list($id,$title,$dateline) = $result->fetch_row())
{
	//����д�뵥���º���
	$writeShowHtml = writeShowHtml($id,$dateline);
}
$result->free();


//д��Ŀҳ
$result = $mysqli->query("select id,folder,pagesize from ".$config['tablePre']."kinds where parent_id>0 and folder<>'' order by id");
while(list($kind_id,$folder,$pagesize) = $result->fetch_row())
{
	//ȷ���ܼ�¼�����������ҳ����
	list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where kind_id=".$kind_id)->fetch_row();
	$pagecount = ceil($recoudcount/$pagesize);
	if ($pagecount == 0) $pagecount = 1;

	//��������Ŀ�ʼҳ�����ҳ
	$i_start = 1;
	$i_end = $pagecount;

	for ($i=$i_start;$i<=$i_end;$i++)
	{
		$writeListHtml = writeListHtml($kind_id,$folder,$i);
	}
}

//д����ҳ
writePhpToHtml();
?>
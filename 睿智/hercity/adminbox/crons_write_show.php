<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

set_time_limit(120);


//�����������ʼ��
$i = _GET('i','i',0);

//ÿ��д���ļ���
$writecount = $config['htmlShowCount'];

//��ȡ�ܼ�¼��
if (isset($_SESSION['recoudcount']))
{
	$recoudcount = $_SESSION['recoudcount'];
}
else
{
	list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where special=0")->fetch_row();
	$_SESSION['recoudcount'] = $recoudcount;
}

$sql = "select id,title,dateline from ".$config['tablePre']."posts where special=0 and is_show order by id desc limit $i,$writecount";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$i_start = $i+1;
$i_end = (($i+$writecount)>$recoudcount)?$recoudcount:($i+$writecount);
$progress = (ceil($i_start/$recoudcount*100))."%";
printf("��%d����¼����ǰ���ڴ����%d��%d��...(%s)<hr />",$recoudcount,$i_start,$i_end,$progress);

while(list($id,$title,$dateline) = $result->fetch_row())
{
	//����д�뵥���º���
	$writeShowHtml = writeShowHtml($id,$dateline);
	if ($writeShowHtml>0) printf ("%d.%s<br />",$id,$title);
}
$result->free();

$i += $writecount;

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
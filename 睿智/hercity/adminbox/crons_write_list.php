<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


//�����������ʼ�㣬Ĭ�ϴӵ�1ҳ��ʼ��
$i = _GET('i','i',1);

//ÿ��д���ļ���
$writecount = $config['htmlListCount'];

//��ǰ�������Ŀ
$kind_id = _GET('kind_id','i');
if (!$kind_id) list($kind_id) = $mysqli->query("select id from ".$config['tablePre']."kinds where is_list and folder<>'' order by id limit 1")->fetch_row();

//ȡ����Ŀ��Ӧ��htmlҳ����·����ÿҳ��¼��
list($folder,$pagesize,$name) = $mysqli->query("select folder,pagesize,name from ".$config['tablePre']."kinds where id=".$kind_id)->fetch_row();

//ȷ���ܼ�¼�����������ҳ����
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren($kind_id).")")->fetch_row();
$pagecount = ceil($recoudcount/$pagesize);
if ($pagecount == 0) $pagecount = 1;

//��������Ŀ�ʼҳ�����ҳ
$i_start = $i;
$i_end = ($pagecount<=$i+$writecount)?$pagecount:($i+$writecount-1);

printf("��ǰ��Ŀ��%s��<br />",$name);
printf("��%dҳ�����ڴ����%d-%dҳ��<br />",$pagecount,$i_start,$i_end);

for ($i=$i_start;$i<=$i_end;$i++)
{
	$writeListHtml = writeListHtml($kind_id,$folder,$i);
	echo ($writeListHtml);
}

echo "<hr />";
//sleep(1);


if ($pagecount>=$i)
{
	printf("<script type=\"text/javascript\">window.location=\"%s?kind_id=%d&i=%d\";</script>",$_SERVER['PHP_SELF'],$kind_id,$i);
}
else
{
	list($kind_id) = $mysqli->query("select id from ".$config['tablePre']."kinds where id>".$kind_id." and is_list and folder<>'' order by id limit 1")->fetch_row();
	$i = 1;
	if ($kind_id)
	printf("<script type=\"text/javascript\">window.location=\"%s?kind_id=%d&i=%d\";</script>",$_SERVER['PHP_SELF'],$kind_id,$i);
	else
	echo "ȫ���������!<a href=\"crons_write_index.php\">����</a>";
}

require_once("includes/footer.php");
?>
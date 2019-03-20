<?php
require_once("inc_header.php");

$tags =  _GET('tags');
$letter =  _GET('letter');
if ($letter != '0') $letter = strtoupper($letter); 
$sign =   _GET('sign');



if ($sign == 'hot')
{
$where = " is_show";
$path = '/list-hot';
$pagetitle = '��Ů���а�';
$order = "baidu_index desc,id desc";
}

if ($sign == 'new')
{
$where = " is_show";
$path = '/list-new';
$pagetitle = '�����ϰ���Ů';
$order = "id desc";
}

if ($tags != '')
{
$where = " is_show and tags like '%".$tags."%'";
$path = '/tags-'.$tags;
$pagetitle = $tags;
$order = "baidu_index desc,id desc";
}

if ($letter != '')
{
$where = " is_show and theletter ='".$letter."'";
$path = '/letter-'.$letter;
$pagetitle = '����ĸ��'.$letter;
$order = "baidu_index desc,id desc";
}





//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page',"i",1);
$pagesize = 32;
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."persons where ".$where)->fetch_row();

if($recordcount == 0){
	echo '�������ҳ�治����';
	exit;
	}

$persons = getArrayFromPersons("where ".$where." order by ".$order." limit ".($page-1)*$pagesize.",".$pagesize);
//���÷�ҳ����
$pages = pages($recordcount,$pagesize,$page,$path.'-%d.html');
$pages = str_replace($tags,urlencode($tags),$pages);

$news = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=12 order by order_id desc,id desc limit 8",36);

$smarty->assign("title",$tags.'���а�_���˰�_�����');
$smarty->assign("pagetitle",$pagetitle);
$smarty->assign("recordcount",$recordcount);
$smarty->assign("persons",$persons);
$smarty->assign("pages",$pages);
$smarty->assign("news",$news);
$smarty->display("list.html");
$mysqli->close();
?>
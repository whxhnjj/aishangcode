<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//��ȡ��Ŀid
$kind_id = 9;

//��ǰλ��
$temp_kind_id = $kind_id;
$location = "";
$i = 0;
while ($temp_kind_id>0)
{
$temp_kind_info = kindsGetInfo($temp_kind_id);
$kind_href = ($temp_kind_info['parent_id']>0) ? $config['basePath'].$config['htmlListPath'].$temp_kind_info['folder']."/" : $config['basePath'].$config['htmlChannelPath'].$temp_kind_info['folder']."/";
		
$temp_kind_id = $temp_kind_info['parent_id'];
$location = sprintf("<a href=\"%s\">%s</a> &gt; ",$kind_href,$temp_kind_info['name']).$location;
$i++;
}
$location = sprintf("<a href=\"%s\">��ҳ</a> &gt; ",$config['basePath']).$location;


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page',"i",1);
list($kind_name,$pagesize,$folder,$template) = $mysqli->query("select name,pagesize,folder,template from ".$config['tablePre']."kinds where id=".$kind_id)->fetch_row();
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where is_show and kind_id = 54 and thumb2<>'' ")->fetch_row();

$page_max_no = $recordcount - ($pagesize * ($page - 1));

if ($kind_name && $recordcount)
{
	//��Ŀ�����б�
	$list = getArrayFromPosts("where is_show and kind_id = 54 and thumb2<>'' order by id desc limit ".($page-1)*$pagesize.",".$pagesize);

	//���÷�ҳ����
	$pages = pages($recordcount,$pagesize,$page,$config['basePath'].$config['htmlListPath'].$folder."/".$config['htmlListFileName']);

	//�Ҳ�����
	$list_new = getArrayFromPosts("where is_show order by id desc limit 10");
	$club_commend = getArrayFromPosts("where is_show and kind_id=45 and thumb2<>'' order by id desc limit 4",14);
	$list_hot = getArrayFromPosts("where is_show and dateline<".(time()-30)." order by hits desc limit 10");
	$list_photo = getArrayFromPosts("where is_show and thumb1<>'' and kind_id in (24,25,26,27) order by id desc limit 3");



	if (!$template) $template = $config['listTemplates'][0];

	$smarty->assign("ads",$ads);
	$smarty->assign("kind_name",$kind_name);
	$smarty->assign("kind_folder",$folder);
	$smarty->assign("title",'ר�� �߻� - �����');
	$smarty->assign("list",$list);
	//$smarty->assign("pages",$pages);
	$smarty->assign("list_new",$list_new);
	$smarty->assign("club_commend",$club_commend);
	$smarty->assign("list_hot",$list_hot);
	$smarty->assign("list_photo",$list_photo);
	$smarty->assign("location",$location);
	$smarty->assign("page_max_no",$page_max_no);
	$smarty->display($template);
}
else
{
	echo "����Ŀ���������ݡ�";
}
$mysqli->close();
?>
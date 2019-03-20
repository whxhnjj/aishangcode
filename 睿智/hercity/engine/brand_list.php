<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$tag = _GET('tag','s','tag');
$auth = _GET('auth','i',0);
$allow = _GET('allow','i',0);

$where = ' where is_show ';

if ($tag == ''){$tag = 'tag';}
if ($tag != 'tag'){$where .= " and tags like '%".$tag."%' ";}
if ($auth == 1){$where .= ' and is_auth ';}
if ($auth == 2){$where .= ' and not is_auth ';}
if ($allow == 1){$where .= ' and is_allow_join ';}
if ($allow == 2){$where .= ' and is_allow_agent ';}


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page',"i",1);
$pagesize = 24;
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."brands ".$where)->fetch_row();

$title = '����Ʒ�� ����Ʒ�� ��ʽ��װƷ��';
$page_max_no = $recordcount - ($pagesize * ($page - 1));

if ($recordcount)
{
	//��Ŀ�����б�
	$list = getArrayFromBrands($where." order by is_auth desc,order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize,'id,firstname,lastname,logo,tags,hits,is_auth');

	//���÷�ҳ����
	$pages = pages($recordcount,$pagesize,$page,$config['basePath']."s/brand/list-".$tag."-".$auth."-".$allow."-%d.html");

	$template = 'brand_list.html';	
	$smarty->assign("tag",$tag);
	$smarty->assign("auth",$auth);
	$smarty->assign("allow",$allow);
	$smarty->assign("title",$title);
	$smarty->assign("list",$list);
	$smarty->assign("pages",$pages);
	$smarty->assign("page_max_no",$page_max_no);
	$smarty->display($template);
}
else
{
	echo "����Ŀ���������ݡ�";
}
$mysqli->close();
?>
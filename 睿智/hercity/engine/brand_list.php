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


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page',"i",1);
$pagesize = 24;
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."brands ".$where)->fetch_row();

$title = '旗袍品牌 华服品牌 中式服装品牌';
$page_max_no = $recordcount - ($pagesize * ($page - 1));

if ($recordcount)
{
	//栏目文章列表
	$list = getArrayFromBrands($where." order by is_auth desc,order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize,'id,firstname,lastname,logo,tags,hits,is_auth');

	//调用分页函数
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
	echo "该栏目下暂无内容。";
}
$mysqli->close();
?>
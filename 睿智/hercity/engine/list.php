<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//获取栏目id
$kind_id = _GET('kind_id',"i");

//当前位置
$temp_kind_id = $kind_id;
$location = "";
$i = 0;
while ($temp_kind_id>0)
{
$temp_kind_info = kindsGetInfo($temp_kind_id);
$kind_href = ($temp_kind_info['parent_id']>0) ? $config['basePath'].$config['htmlListPath'].$temp_kind_info['folder']."/" : $config['basePath'].$config['htmlChannelPath'].$temp_kind_info['folder']."/";
		
$temp_kind_id = $temp_kind_info['parent_id'];
$location = sprintf(" &gt; <a href=\"%s\">%s</a>",$kind_href,$temp_kind_info['name']).$location;
$i++;
}
$location = sprintf("<a href=\"%s\">首页</a> ",$config['basePath']).$location;

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page',"i",1);
list($kind_name,$pagesize,$folder,$template,$title,$keywords,$description) = $mysqli->query("select name,pagesize,folder,template,title,keywords,description from ".$config['tablePre']."kinds where id=".$kind_id)->fetch_row();
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren($kind_id).")")->fetch_row();

$title = $title ? $title : $kind_name;
$page_max_no = $recordcount - ($pagesize * ($page - 1));

if (! $kind_name)
{
	echo "无此栏目。";
	exit;

}


//if ($kind_name && $recordcount)
//{
	//栏目文章列表
	$list = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren($kind_id).") order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize);

	//调用分页函数
	$pages = pages($recordcount,$pagesize,$page,$config['basePath'].$config['htmlListPath'].$folder."/".$config['htmlListFileName']);

	//右侧文章
	$list_new = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id not in (8,9,10,11,47,48,43,64,70,71,72,73) and special=0 order by id desc limit 10",44);
	$club_commend = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=45 and thumb2<>'' order by id desc limit 4",14);
	$list_hot = getArrayFromPosts("where is_show and thumb1<>'' and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 5",40);
	$list_photo = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and thumb1<>'' and kind_id in (24,25,26,27) order by id desc limit 3",20);
	$list_keywords = getArrayFromKeywords("order by hits desc limit 80");
	
	if (!$template) $template = $config['listTemplates'][0];

	$smarty->assign("kind_name",$kind_name);
	$smarty->assign("kind_folder",$folder);
	$smarty->assign("title",$title);
	$smarty->assign("keywords",$keywords);
	$smarty->assign("description",$description);
	$smarty->assign("list",$list);
	$smarty->assign("pages",$pages);
	$smarty->assign("list_new",$list_new);
	$smarty->assign("club_commend",$club_commend);
	$smarty->assign("list_hot",$list_hot);
	$smarty->assign("list_photo",$list_photo);
	$smarty->assign("list_keywords",$list_keywords);
	$smarty->assign("location",$location);
	$smarty->assign("page_max_no",$page_max_no);
	$smarty->display($template);
//}
//else
//{
//	echo "该栏目下暂无内容。";
//}
$mysqli->close();
?>
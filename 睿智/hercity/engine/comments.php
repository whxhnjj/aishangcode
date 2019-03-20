<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//获取文章id
$id = _GET('post_id',"i");

$sql = "select id,title,dateline,kind_id,hits,comments,thumb1 from ".$config['tablePre']."posts where id=".$id;
if ($result = $mysqli->query($sql))
{
	if ($post = $result->fetch_array())
	{
		$post['title_encode'] = rawurlencode($post['title']);

		$post['url'] = getPostPath($id).sprintf($config['htmlShowFileName'],$id);
		
		//当前位置
		$temp_kind_id = $post['kind_id'];
		$location = "<a href=\"".$post['url']."\">".$post['title']."</a> &gt; 网友评论";
		$i = 0;
		while ($temp_kind_id>0)
		{
		$temp_kind_info = kindsGetInfo($temp_kind_id);
		$kind_href = ($temp_kind_info['parent_id']>0) ? $config['basePath'].$config['htmlListPath'].$temp_kind_info['folder']."/" : $config['basePath'].$config['htmlChannelPath'].$temp_kind_info['folder']."/";

		$temp_kind_id = $temp_kind_info['parent_id'];
		$location = sprintf("<a href=\"%s\">%s</a> &gt; ",$kind_href,$temp_kind_info['name']).$location;
		$i++;
		}
		$location = sprintf("<a href=\"%s\">首页</a> &gt; ",$config['basePath']).$location;
		
		
		//文章
		$list_hot = getArrayFromPosts("where is_show and thumb1<>'' and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 5",40);
	}
	else
	{
		echo "没有找到对应的内容。";
		exit;
	}
$result->free();	
}


//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page',"i",1);
$pagesize = 10;
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."comments where is_show and post_id=".$id)->fetch_row();

//评论列表
$list_comments = getArrayFromComments("post_id=".$id,"id desc",($page-1)*$pagesize,$pagesize);
//分页
$pages = pages($recordcount,$pagesize,$page,$id."-%d.html");


$template = "comments.html";

$smarty->assign("post",$post);
$smarty->assign("location",$location);
$smarty->assign("list_comments",$list_comments);
$smarty->assign("pages",$pages);
$smarty->assign("list_hot",$list_hot);
$smarty->display($template);

$mysqli->close();
?>
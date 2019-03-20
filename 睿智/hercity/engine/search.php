<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//获取搜索的key
$tag = _GET('q','s');
if (strlen($tag) > 20){header('location:/');}
$title = $tag." 搜索结果";
$location = '<a href="/">首页</a>  &gt; '.$title;



//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('p','i',1);
$pagesize = 10;
$sql = "select count(id) from ".$config['tablePre']."posts where is_show and special=0 and (title like '%".$tag."%' or keyword like '%".$tag."%' or brief like '%".$tag."%')";
$result = $mysqli->query($sql);
list($recordcount) = $result->fetch_row();

//文章列表
$search = getArrayFromPosts("where is_show and special=0 and (title like '%".$tag."%' or keyword like '%".$tag."%' or brief like '%".$tag."%') order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize);

function findKey(&$value,$key,$tag)
{
	if ($key == 'title' || $key == 'brief')$value = str_replace($tag,"<em>$tag</em>",$value);
}


for ($i=0;$i<count($search);$i++)
{
array_walk($search[$i],"findKey",$tag);
}


//以下语句会连带处理到thumb等其它字段所以改用以上循环。
//if ($search) array_walk($search,"findKey",$tag);

//调用分页函数
$pages = pages($recordcount,$pagesize,$page,"/tag/?q=".$tag."&p=%d");

//文章
$list_hot = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." and thumb1<>'' order by hits desc limit 5",40);


$template = "search.html";

$smarty->assign("title",$title);
$smarty->assign("location",$location);
$smarty->assign("key",$tag);
$smarty->assign("search",$search);
$smarty->assign("pages",$pages);
$smarty->assign("list_hot",$list_hot);

$smarty->display($template);
$mysqli->close();
?>
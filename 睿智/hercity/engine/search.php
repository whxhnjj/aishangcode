<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//��ȡ������key
$tag = _GET('q','s');
if (strlen($tag) > 20){header('location:/');}
$title = $tag." �������";
$location = '<a href="/">��ҳ</a>  &gt; '.$title;



//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('p','i',1);
$pagesize = 10;
$sql = "select count(id) from ".$config['tablePre']."posts where is_show and special=0 and (title like '%".$tag."%' or keyword like '%".$tag."%' or brief like '%".$tag."%')";
$result = $mysqli->query($sql);
list($recordcount) = $result->fetch_row();

//�����б�
$search = getArrayFromPosts("where is_show and special=0 and (title like '%".$tag."%' or keyword like '%".$tag."%' or brief like '%".$tag."%') order by order_id desc,id desc limit ".($page-1)*$pagesize.",".$pagesize);

function findKey(&$value,$key,$tag)
{
	if ($key == 'title' || $key == 'brief')$value = str_replace($tag,"<em>$tag</em>",$value);
}


for ($i=0;$i<count($search);$i++)
{
array_walk($search[$i],"findKey",$tag);
}


//����������������thumb�������ֶ����Ը�������ѭ����
//if ($search) array_walk($search,"findKey",$tag);

//���÷�ҳ����
$pages = pages($recordcount,$pagesize,$page,"/tag/?q=".$tag."&p=%d");

//����
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
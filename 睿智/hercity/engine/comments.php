<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//��ȡ����id
$id = _GET('post_id',"i");

$sql = "select id,title,dateline,kind_id,hits,comments,thumb1 from ".$config['tablePre']."posts where id=".$id;
if ($result = $mysqli->query($sql))
{
	if ($post = $result->fetch_array())
	{
		$post['title_encode'] = rawurlencode($post['title']);

		$post['url'] = getPostPath($id).sprintf($config['htmlShowFileName'],$id);
		
		//��ǰλ��
		$temp_kind_id = $post['kind_id'];
		$location = "<a href=\"".$post['url']."\">".$post['title']."</a> &gt; ��������";
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
		
		
		//����
		$list_hot = getArrayFromPosts("where is_show and thumb1<>'' and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 5",40);
	}
	else
	{
		echo "û���ҵ���Ӧ�����ݡ�";
		exit;
	}
$result->free();	
}


//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page',"i",1);
$pagesize = 10;
list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."comments where is_show and post_id=".$id)->fetch_row();

//�����б�
$list_comments = getArrayFromComments("post_id=".$id,"id desc",($page-1)*$pagesize,$pagesize);
//��ҳ
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
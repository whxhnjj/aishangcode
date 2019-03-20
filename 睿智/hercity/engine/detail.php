<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");


$id = _GET('id',"i");
$page_no = _GET('page',"i",1);


$where = "id=".$id;
if(!isset($_SESSION['username'])){
	$where .= " and is_show";
}

$sql = "select id,title,subhead1,subhead2,keyword,brief,author,source,dateline,mediafile,template,kind_id,hits,point,comments,thumb1,linkto,is_show,order_id from ".$config['tablePre']."posts where ".$where; //δ���������²�֧�����߿��������Ի�������¡�
if ($result = $mysqli->query($sql))
{
	if ($post = $result->fetch_array())
	{
		$sql = "select content from ".$config['tablePre']."postcontents where post_id=".$id." and page_no=".$page_no;
		$result = $mysqli->query($sql) or die("��ѯʧ��");
		list($content)=$result->fetch_row() or $content='';
		$result->free();

		//��content����һϵ�д���
		$keyword_string = '\''.str_replace(',','\',\'',$post['keyword']).'\'';
		$sql = "select name,ename from ".$config['tablePre']."persons where name in (".$keyword_string.")";
		$result = $mysqli->query($sql);
		while (list($name,$ename) = $result->fetch_row())
		{
			//ֻ�滻��һ��������Ҫ������
			$content = preg_replace('/'.$name.'/','<a href="http://mei.hercity.com/'.$ename.'/" target="_blank" title="'.$name.'��������">'.$name.'</a>',$content,1);
		}
		$content = str_replace('alt="" ','alt="'.$post['title'].'" ',$content);
		$content = str_replace('<div>&nbsp;</div>','',$content);
		$content = str_replace('<p>&nbsp;</p>','',$content);

		$post['content'] = $content;
		$post['title_encode'] = rawurlencode($post['title']);
		$post['url'] = $config['baseUrl'].getPostPath($id).sprintf($config['htmlShowFileName'],$id);
		
		$post['brief'] = str_replace("\r\n",'<br />',$post['brief']);
		
		$smarty->assign('post',$post);
		$smarty->assign('keywords',$post['subhead1'].','.$post['keyword']);
		$smarty->assign('description',str_replace('"','',$post['brief']));
		
		//��ǰλ��
		$temp_kind_id = $post['kind_id'];
		$location = $post['title'];
		$i = 0;
		while ($temp_kind_id>0)
		{
		$temp_kind_info = kindsGetInfo($temp_kind_id);
		$kind_href = ($temp_kind_info['parent_id']>0) ? $config['basePath'].$config['htmlListPath'].$temp_kind_info['folder'].'/' : $config['basePath'].$config['htmlChannelPath'].$temp_kind_info['folder'].'/';

		$temp_kind_id = $temp_kind_info['parent_id'];
		$location = sprintf("<a href=\"%s\">%s</a> &gt; ",$kind_href,$temp_kind_info['name']).$location;
		$i++;
		}
		$location = sprintf("<a href=\"%s\">��ҳ</a> &gt; ",$config['basePath']).$location;
		$smarty->assign("location",$location);
		
		
		//��һƪ��һƪ
		$next = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=".$post['kind_id']." and id<".$id." order by id desc limit 1");
		$prev = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=".$post['kind_id']." and id>".$id." order by id limit 1");

		//����
		$list_new = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and  kind_id not in (8,9,10,11,47,48,43,64,70,71,72,73) and special=0 order by rand() limit 10",44);
		$list_photo = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and thumb1<>'' and kind_id in (24,25,26,27,28,53) order by rand() limit 5",20);		
		if (! $list_photo) $list_photo = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and thumb1<>'' and kind_id in (24,25,26,27,28,53) order by id desc limit 5",20);


		
		
		//ͼƬ��
		$photos = getArrayFromAtts("where post_id = ".$id." order by id");


		//���������Ӱ��
		if ($post['keyword'] != "")
		{
			$array_keyword = explode(",",$post['keyword']);
			$where = "";
			while($key=current($array_keyword))
			{
			$where .= "or keyword like '%".$key."%' ";
			next($array_keyword);
			}
			if ($where) $where = "and (".substr($where,3).")";
			$list_same = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and special = 0 and id<>".$id." ".$where." order by id desc limit 12",44);
		}

		//���ܻ�ϲ����Ӱ��������������ʱ����������Ͷ����µ�������
		$list_photo_like = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (24,25,26,27) and template='detail_photo.html' and id<>".$id." order by rand() limit 3",34);
		if (! $list_photo_like)  $list_photo_like = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (24,25,26,27) and template='detail_photo.html' and id<>".$id." order by id desc limit 3",34);


		//����
		$list_comments = getArrayFromComments("post_id=".$id,"id desc",0,20);
		$smarty->assign("list_comments",$list_comments);

		
		$list_hot = getArrayFromPosts("where is_show and thumb1<>'' and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-25920000)." order by hits desc limit 5",40);
		$list_org = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren(4).") order by hits desc limit 12",44);
		$smarty->assign("list_same",$list_same);
		$smarty->assign("list_photo_like",$list_photo_like);

		$smarty->assign("next",$next);
		$smarty->assign("prev",$prev);
		$smarty->assign("list_new",$list_new);
		$smarty->assign("list_hot",$list_hot);
		$smarty->assign("list_org",$list_org);
		$smarty->assign("list_photo",$list_photo);

		$smarty->assign("photos",$photos);
		$smarty->assign("post_keyword",$array_keyword);
		
		

		
		//��ָ��ģ�����
		if ($post['template']=='') $post['template'] = $config['showTemplates'][0];
		
//if ($post['template']=="detail.html") {$post['template']="detail1.html";}
		$smarty->display($post['template']);
		
	}
	else
	{
		echo "û���ҵ���Ӧ�����ݡ�";
	}
}

$mysqli->close();
?>
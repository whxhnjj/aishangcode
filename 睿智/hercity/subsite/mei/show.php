<?php
require_once("inc_header.php");

//ֱ���ж��ֻ���
if (isMobile())
{
$smarty->template_dir = "templates/mobile/";
}

$id = _GET('id',"i");
$ename =  _GET('ename');


if ($ename != '')
{
	$sql = "select id,name,oname,ename,tags,baidu_index,hits,fans,thumb1,thumb2,profile,archives,website,weibo,title,keywords,description from ".$config['tablePre']."persons where is_show and ename='".$ename."'";
}
else
{
	$sql = "select id,name,oname,ename,tags,baidu_index,hits,fans,thumb1,thumb2,profile,archives,website,weibo,title,keywords,description from ".$config['tablePre']."persons where is_show and id=".$id;
}

if ($result = $mysqli->query($sql))
{
	$row = $result->fetch_array();

	$archives = explode("\r",$row['archives']);
	foreach($archives as $archive)
	{
		$arr_archive = explode("��",trim($archive,"��"));
		$arch[] = $arr_archive;
	}
	
	//var_dump($arch);
	$row['archives'] = $arch;
	$row['thumb1'] = $row['thumb1'] ? $row['thumb1'] : 'nophoto_160_170.gif';
	$row['thumb1'] = $config['basePath'].$config['thumbPath'].$row['thumb1'];
	$row['thumb2'] = $row['thumb2'] ? $row['thumb2'] : 'nophoto_234_320.gif';
	$row['thumb2'] = $config['basePath'].$config['thumbPath'].$row['thumb2'];
	$row['profile'] = str_replace("\r","<br />",$row['profile']);
	$row['title'] = ($row['title'] != '') ? $row['title'].'_���˰�' : $row['name'].'��������_'.$row['name'].'���_���˰�';
	//$row['title'] = $row['name'].'��������_���˰�';
	$row['tags'] = explode(',',$row['tags']);
	
	$smarty->assign("person",$row);


	//�������
	$list_news = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and keyword like '%".$row['name']."%' and template <> 'show_photo.html' and kind_id <> 38 order by id desc limit 10");
	$list_photos = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and thumb1<>'' and keyword like '%".$row['name']."%' and template = 'show_photo.html' order by id desc limit 5",20);		
	if (isset($list_news)) $smarty->assign("list_news",$list_news);
	if (isset($list_photos)) $smarty->assign("list_photos",$list_photos);

		
	//��ָ��ģ�����
	$smarty->display('show.html');
	$result->free();	
}

$mysqli->close();

?>
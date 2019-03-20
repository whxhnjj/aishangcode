<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//��������ȡͼƬ������ģ��
$modifier = '/(\/'.str_replace('/','\/',$config['attachmentPath']).'[0-9]{4}\/[0-9]{2}\/[0-9]{18,22}\.(jpg|jpeg|gif|png))/';

$channel = _GET('c');
switch ($channel)
{
case 'club':
	$where = " and p.id=c.post_id and p.kind_id=k.id and p.kind_id in (43,44,45) ";
	$title = '������ۻ�ݾ����Ƽ�';
	$description = '������ۻ��ÿ�ܾ��������Ƽ���';
	break;
default:
	$where = "and p.id=c.post_id and p.kind_id=k.id and p.kind_id not in (".kindsGetChildren(8).") and p.kind_id not in (".kindsGetChildren(9).") and p.kind_id not in (".kindsGetChildren(10).") and p.kind_id not in (".kindsGetChildren(11).") ";
	$title = '�����ȫվ���ݸ���';
	$description = '�������ȫ��Ψһ���й���Ů����ý�������ڴ���һ����߷��ġ��㼯������Ϊ������й�ʽʱ��Ԫ�ص�Ů����վ�����Ϊ������ϲ���й�Ԫ�ء�׷���Ʒζ����Ķ���֪��Ů���ṩ��һ��չʾ�뽻����ƽ̨��';
}

$smarty->assign("title",$title);
$smarty->assign("description",$description);

$sql = "select p.id,p.title,p.brief,p.author,c.content,p.dateline,p.linkto,p.kind_id,k.name,k.folder from ".$config['tablePre']."posts as p,".$config['tablePre']."postcontents as c,".$config['tablePre']."kinds as k where p.is_show ".$where." order by id desc limit 20";
$result = $mysqli->query($sql);
while (list($id,$title,$brief,$author,$content,$dateline,$linkto,$kind_id,$kind_name,$kind_folder) = $result->fetch_row()) {


	$href = trim($linkto)?$linkto:$config['baseUrl'].getPostPath($id).sprintf($config['htmlShowFileName'],$id);
	
	preg_match_all($modifier,$content,$b);

	$content = str_replace('src="/upfiles/','src="'.$config['baseUrl'].'/upfiles/',$content);

	$the_photo = $photo = '';
	if (count($b[0]) > 0){
		$the_photo = substr($b[1][0],1);
		$photo = array('url'=>$config['baseUrl'].$config['basePath'].$the_photo,'length'=>filesize('../'.$the_photo),'type'=>mime_content_type('../'.$the_photo));
	}
	

	$list[] = array(
		'id'=>$id,
		'title'=>$title,
		'brief'=>$brief,
		'author'=>$author,
		'dateline'=>$dateline,
		'kind_id'=>$kind_id,
		'kind_name'=>$kind_name,
		'kind_folder'=>$kind_folder,
		'href'=>$href,
		'photo'=>$photo,
		'content'=>$content
	);
}

$smarty->assign("list",$list);
$template = _GET('template','s','rss.xml');
$smarty->display($template);

$mysqli->close();
?>
<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//从内容中取图片的正则模型
$modifier = '/(\/'.str_replace('/','\/',$config['attachmentPath']).'[0-9]{4}\/[0-9]{2}\/[0-9]{18,22}\.(jpg|jpeg|gif|png))/';

$channel = _GET('c');
switch ($channel)
{
case 'club':
	$where = " and p.id=c.post_id and p.kind_id=k.id and p.kind_id in (43,44,45) ";
	$title = '倾城旗袍会馆精华推荐';
	$description = '倾城旗袍会馆每周精华内容推荐。';
	break;
default:
	$where = "and p.id=c.post_id and p.kind_id=k.id and p.kind_id not in (".kindsGetChildren(8).") and p.kind_id not in (".kindsGetChildren(9).") and p.kind_id not in (".kindsGetChildren(10).") and p.kind_id not in (".kindsGetChildren(11).") ";
	$title = '倾城网全站内容更新';
	$description = '倾城网是全球唯一的中国风女性网媒。致力于打造一个别具风格的、汇集以旗袍为代表的中国式时尚元素的女性网站。倾城为国内外喜欢中国元素、追求高品味生活的都市知性女人提供了一个展示与交流的平台。';
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
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
$kind_id = _POST('kind_id','i');
$order_id = _POST('order_id','i');
$template = _POST('template');
$title = _POST('title');
$subhead1 = _POST('subhead1')?_POST('subhead1'):$title;
$subhead2 = _POST('subhead2')?_POST('subhead2'):$title;
$keyword = _POST('keyword');
$keyword = formatKeywords($keyword);
$keyword_original =  _POST('keyword_original');
$author = _POST('author');
$source = _POST('source');
$linkto = _POST('linkto','t');
$content = _POST('content','t');
$brief = _POST('brief');
$thumb1 = _POST('thumb1');
$thumb2 = _POST('thumb2');
$mediafile = _POST('mediafile');
$is_show = _POST('is_show','i',1);
$arrposid = isset($_POST['arrposid']) ? implode(',',_POST('arrposid','as')) : '';
$special = _POST('special','i');
$y = _POST('y','i');
$m = _POST('m','i');
$d = _POST('d','i');
$h = _POST('h','i');
$i = _POST('i','i');
$s = _POST('s','i');
$dateline = mktime($h,$i,$s,$m,$d,$y);
$page_no = _POST('page_no','i',1);
$ok = _POST('ok');


$sql = "update ".$config['tablePre']."posts set kind_id=".$kind_id.",template='".$template."',order_id=".$order_id.",title='".$title."',subhead1='".$subhead1."',subhead2='".$subhead2."',keyword='".$keyword."',author='".$author."',source='".$source."',linkto='".$linkto."',brief='".$brief."',thumb1='".$thumb1."',thumb2='".$thumb2."',mediafile='".$mediafile."',is_show=".$is_show.",arrposid='".$arrposid."',special=".$special.",dateline=".$dateline." where id=".$id;
$result = $mysqli->query($sql);

if ($content != '')
{
	list($contentcount) = $mysqli->query("select count(post_id) as count from ".$config['tablePre']."postcontents where post_id=".$id." and page_no=".$page_no)->fetch_row();
	
	if ($contentcount > 0)
	{
		$sql = "update ".$config['tablePre']."postcontents set content='".$content."' where post_id=".$id." and page_no=".$page_no;
		$result = $mysqli->query($sql);
	}
	else
	{
		$sql = "insert into ".$config['tablePre']."postcontents (post_id,page_no,content) values (".$id.",".$page_no.",'".$content."')";
		$result = $mysqli->query($sql);
	}
}


//更新keywords表
if ($config['autoUpdateKeywords'])
{
	updateKeywords($keyword,$keyword_original);
}


//写静态文件
if ($special == 0 && $is_show == 1)
{
$writeShowHtml = writeShowHtml($id,$dateline);
}

//从内容中提取出图片附件对应的id，修改附件表中相应记录的post_id
preg_match_all("/id=\\\\\"img_([0-9]+)\\\\\"/U",$content,$arr_attachments);

$sql = "update ".$config['tablePre']."attachments set post_id=0 where post_id=".$id;
$result = $mysqli->query($sql);

if ($attachments = implode(",",$arr_attachments[1]))
{
$sql = "update ".$config['tablePre']."attachments set post_id=".$id." where id in(".$attachments.")";
$result = $mysqli->query($sql);
}

$url = $ok == '保存并返回' ? $_POST["url"] : $_SERVER["HTTP_REFERER"];
redirect("文章修改成功",$url);
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$kind_id = _POST('kind_id','i');
$order_id = _POST('order_id','i');
$template = _POST('template');
$title = _POST('title');
$subhead1 = _POST('subhead1')?_POST('subhead1'):$title;
$subhead2 = _POST('subhead2')?_POST('subhead2'):$title;
$keyword = _POST('keyword');
$keyword = formatKeywords($keyword);
$author = _POST('author');
$source = _POST('source');
$linkto = _POST('linkto','t');
$content = _POST('content','t');
$brief = _POST('brief');
$pic = '';
$thumb1 = _POST('thumb1');
$thumb2 = _POST('thumb2');
$mediafile = _POST('mediafile');
$is_show = _POST('is_show','i',1);
$arrposid = isset($_POST['arrposid']) ? implode(',',_POST('arrposid','as')) : '';
$special = _POST('special','i');
$is_tencentweibo = _POST('is_tencentweibo','i',0);
$is_sinaweibo = _POST('is_sinaweibo','i',0);
$is_renren = _POST('is_renren','i',0);

$y = _POST('y','i');
$m = _POST('m','i');
$d = _POST('d','i');
$h = _POST('h','i');
$i = _POST('i','i');
$s = _POST('s','i');
$dateline = mktime($h,$i,$s,$m,$d,$y);
//$dateline=time();
$page_no = _POST('page_no','i',1);
$ok = _POST('ok');

if (trim($title) == "" or (trim($linkto) == "" and trim($content) == ""))
{
echo ("数据填写不完整。");
exit;
}

$sql = "insert into ".$config['tablePre']."posts (kind_id,template,order_id,title,subhead1,subhead2,keyword,author,source,dateline,linkto,brief,thumb1,thumb2,mediafile,is_show,arrposid,hits,comments,point,special,user_id) values (".$kind_id.",'".$template."',".$order_id.",'".$title."','".$subhead1."','".$subhead2."','".$keyword."','".$author."','".$source."',".$dateline.",'".$linkto."','".$brief."','".$thumb1."','".$thumb2."','".$mediafile."',".$is_show.",'".$arrposid."',0,0,0,".$special.",".$_SESSION['userid'].")";
$result = $mysqli->query($sql);
$insert_id = $mysqli->insert_id;

if ($content != '')
{
$sql = "insert into ".$config['tablePre']."postcontents (post_id,page_no,content) values (".$insert_id.",".$page_no.",'".$content."')";
$result = $mysqli->query($sql);
}

//更新keywords表
if ($config['autoUpdateKeywords'])
{
updateKeywords($keyword);
}


//从内容中提取出图片附件对应的id，修改附件表中相应记录的post_id
preg_match_all("/id=\\\\\"img_([0-9]+)\\\\\"/U",$content,$arr_attachments);

if ($attachments = implode(",",$arr_attachments[1]))
{
$sql = "update ".$config['tablePre']."attachments set post_id=".$insert_id." where id in(".$attachments.")";
$result = $mysqli->query($sql);
}


//写静态文件
if ($special == 0 && $is_show == 1)
{
$writeShowHtml = writeShowHtml($insert_id,$dateline);
}

$url = $_POST["url"] ? $_POST["url"] : "posts_index.php?kind_id=".$kind_id;
$url = $ok == '保存并返回' ? $url : $_SERVER["HTTP_REFERER"];



redirect("文章新建成功",$url);

require_once("includes/footer.php");
?>
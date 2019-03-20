<?php
require_once('ini.php');
$id = $_GET['id'];
$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
$pagesize = 20;

//从微信身份验证页回来
if (isset($_GET['wechat_str']))
{
	$str = $_GET['wechat_str'];
	$obj = json_decode($str);
	$_SESSION['wechat_userinfo'] = array('openid'=>$obj->openid, 'nickname'=>utf8ToGbk($obj->nickname), 'sex'=>$obj->sex, 'province'=>utf8ToGbk($obj->province), 'city'=>utf8ToGbk($obj->city), 'headimgurl'=>$obj->headimgurl);
}
$smarty->assign("session",isset($_SESSION)?$_SESSION:'');

//微信身份认证
if ((!isset($_SESSION['wechat_userinfo']) || $_SESSION['wechat_userinfo'] == '') )
{
	//转入微信身份验证页以获取openid
	header('location:'.WECHAT_AUTHORIZE_URL.'?appid='.WECHAT_APPID.'&appsecret='.WECHAT_APPSECRET.'&scope=snsapi_userinfo&backurl='.urlencode(BASE_URL.'/comment_list.php?id='.$id.'&page='.$page));
	exit;
}

$post = getPostDetail($id);
$comments = getCommentList($pagesize,$page,$id);


$smarty->assign("post",$post);
$smarty->assign("comments",$comments);
$smarty->assign("page",$page);
$smarty->display('comment_list.html');
?>
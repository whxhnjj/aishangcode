<?php
require_once('ini.php');

$post_id = _POST('post_id','i',0);
$content = _POST('content');
$content = utf8ToGbk($content);
$name = $_SESSION['wechat_userinfo']['nickname'];
$wechat_openid = $_SESSION['wechat_userinfo']['openid'];
$wechat_headimgurl = $_SESSION['wechat_userinfo']['headimgurl'];

//判断登录是否失效
if (!isset($_SESSION['wechat_userinfo'])){
	echo '-1';
	exit;
}

//内容为空
if ($content == ''){
	echo '-2';
	exit;
}

$comment_id = setComment($post_id,$name,$content,$wechat_openid,$wechat_headimgurl);
echo $comment_id;
?>
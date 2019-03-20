<?php
//本文件为微信公众平台网页授权文档。使用方法如下：
/*
跳转至 http://m.hercity.com/_wechat/authorize.php?appid=XXXXX&appsecret=XXXXXXXXXXX&scope=XXXXXXXX&backurl=XXXXXXXXXXXXXXXXXXXXX
其中appid,appsecret是微信公众平台提供的。
scope取值snsapi_base或snsapi_userinfo。含议参见微信网页授权文档
backurl是授权完成后返回的地址。需用urlencode编码。
*/

session_start();

if (isset($_GET['code']))
{
	$code = $_GET['code'];
	$backurl = $_GET['state'];

	if (strpos($backurl,'?')){$sign = '&';}else{$sign = '?';}
	$str = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$_SESSION['appid'].'&secret='.$_SESSION['appsecret'].'&code='.$code.'&grant_type=authorization_code');
	$obj = json_decode($str);
	
	
	//拉取用户详细信息
	if ($obj->scope == 'snsapi_userinfo')
	{
		$str = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$obj->access_token.'&openid='.$obj->openid.'&lang=zh_CN');
	}
	

	header('location:'.$backurl.$sign.'wechat_str='.$str);
}
else
{
	$_SESSION['appid'] = $_GET['appid'];
	$_SESSION['appsecret'] = $_GET['appsecret'];
	$backurl = $_GET['backurl'];
	$scope = $_GET['scope'];
	header('location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$_GET['appid'].'&redirect_uri='.urlencode('http://'.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF']).'&response_type=code&scope='.$scope.'&state='.urlencode($backurl).'#wechat_redirect');
}
?>
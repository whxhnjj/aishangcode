<?php
//���ļ�Ϊ΢�Ź���ƽ̨��ҳ��Ȩ�ĵ���ʹ�÷������£�
/*
��ת�� http://m.hercity.com/_wechat/authorize.php?appid=XXXXX&appsecret=XXXXXXXXXXX&scope=XXXXXXXX&backurl=XXXXXXXXXXXXXXXXXXXXX
����appid,appsecret��΢�Ź���ƽ̨�ṩ�ġ�
scopeȡֵsnsapi_base��snsapi_userinfo������μ�΢����ҳ��Ȩ�ĵ�
backurl����Ȩ��ɺ󷵻صĵ�ַ������urlencode���롣
*/

session_start();

if (isset($_GET['code']))
{
	$code = $_GET['code'];
	$backurl = $_GET['state'];

	if (strpos($backurl,'?')){$sign = '&';}else{$sign = '?';}
	$str = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$_SESSION['appid'].'&secret='.$_SESSION['appsecret'].'&code='.$code.'&grant_type=authorization_code');
	$obj = json_decode($str);
	
	
	//��ȡ�û���ϸ��Ϣ
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
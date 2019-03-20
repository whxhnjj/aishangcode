<?php
//本文件为微信公众平台access_token中控文件。
//所有接口都需通过本文件来获取access_token以免因为刷新导致相互冲突
//调用地址 http://m.hercity.com/_wechat/access_token.php?appid=XXXX&appsecret=XXXXX


$appid = $_GET['appid'];
$appsecret = $_GET['appsecret'];
$data = json_decode(file_get_contents("../data/access_token.json"));

if ($data->expire_time < time()) {

	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
	$res = json_decode(file_get_contents($url));
	$access_token = $res->access_token;
	if ($access_token) {
		$data->expire_time = time() + 7000;
		$data->access_token = $access_token;
		$fp = fopen("../data/access_token.json", "w");
		fwrite($fp, json_encode($data));
		fclose($fp);
	}
}
else
{
	$access_token = $data->access_token;
}

echo $access_token;
?>
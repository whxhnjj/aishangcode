<?php
//���ļ�Ϊ΢�Ź���ƽ̨access_token�п��ļ���
//���нӿڶ���ͨ�����ļ�����ȡaccess_token������Ϊˢ�µ����໥��ͻ
//���õ�ַ http://m.hercity.com/_wechat/access_token.php?appid=XXXX&appsecret=XXXXX


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
<?php
//本文件为微信公众平台jsapi_ticket全局中控文件。
//所有接口都需通过本文件来获取jsapi的ticket以免因为多次获取触发频率限制
//调用地址 http://m.hercity.com/_wechat/jsapi_ticket.php?access_token=XXXX


$access_token = $_GET['access_token'];
$datafile = '../data/jsapi_ticket.json';
$data = json_decode(file_get_contents($datafile));

if ($data->expire_time < time()) {

	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";
	$res = json_decode(file_get_contents($url));
	//var_dump($res);
	$ticket = $res->ticket;
	if ($ticket) {
		$data->expire_time = time() + 7000;
		$data->jsapi_ticket = $ticket;
		$fp = fopen($datafile, "w");
		fwrite($fp, json_encode($data));
		fclose($fp);
	}
	else
	{
		//如果读不到ticket，尝试增加原有ticket的有效期
		$data->expire_time = time() + 100;
		$fp = fopen($datafile, "w");
		fwrite($fp, json_encode($data));
		fclose($fp);
	}
}
else
{
	$ticket = $data->jsapi_ticket;
}

echo $ticket;
?>
<?php
//���ļ�Ϊ΢�Ź���ƽ̨jsapi_ticketȫ���п��ļ���
//���нӿڶ���ͨ�����ļ�����ȡjsapi��ticket������Ϊ��λ�ȡ����Ƶ������
//���õ�ַ http://m.hercity.com/_wechat/jsapi_ticket.php?access_token=XXXX


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
		//���������ticket����������ԭ��ticket����Ч��
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
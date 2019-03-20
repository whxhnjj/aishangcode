<?php
require_once("../includes/interface.php");
require_once("../includes/ucenter.php");
$is_err = 0;

//��Ա��¼
if(!empty($_COOKIE['loftcms_auth'])) {
	list($uid, $u_name) = explode("\t", uc_authcode($_COOKIE['loftcms_auth'], 'DECODE'));
	$login_return = 0;
} else {
	$username = _POST('username','S');
	$password = _POST('password','p');

		list($uid, $u_name, $password ,$email) = uc_user_login($username, $password);
		if($uid > 0)
		{
			//�û���½�ɹ������� Cookie������ֱ���� uc_authcode �������û�ʹ���Լ��ĺ���
			setcookie('loftcms_auth', uc_authcode($uid."\t".$u_name, 'ENCODE'), time()+3600*24);
			//����ͬ����¼�Ĵ���
			$ucsynlogin = uc_user_synlogin($uid);
			$login_return = $uid.','.gbkToUtf8($u_name).','.$ucsynlogin;
		}
		else
		{
			$login_return = $uid;
			$is_err = 1;
		}
	}



//��ȡ����
$post_id = _POST('post_id','i',0);
$to_id = _POST('to_id','i',0);
$dateline = time();
$ip = $_SERVER['REMOTE_ADDR'];
$point = _POST('point','i',0);
$is_show = $config['commentDefaultStatus'];
$content = _POST('content','S');

//���������еĻ���
preg_match_all("/#(.{1,20})#/U",$content,$arr_topic);
while ($topic = current($arr_topic[1]))
{
	$sql = "update ".$config['tablePre']."topics set comments=comments+1 where title='".$topic."'";
	$result = $mysqli->query($sql);
	
	if ($mysqli->affected_rows == 0) //���ⳤ�Ȳ��ܳ�20
	{
		$sql = "insert into ".$config['tablePre']."topics (title,comments) values ('".$topic."',1)";
		$mysqli->query($sql);
	}
next($arr_topic[1]);
}



//�����Ƿ������д�
$specialWords = explode(",",$config['specialWords']);
while ($specialWord = current($specialWords))
{
//�˴�strpos��ֵ�����0(����ͷƥ��)���ᱻ���Ϊ��ͬ�մ���������(string)ǿ��תΪ�ַ�����
	if ((string)strpos(gbkToUtf8($content),gbkToUtf8($specialWord))!='')
	{
		$is_err = 2;
		break;
	}
	next($specialWords);
}

//�����Ƿ�Ϊ��
if ($content == '') $is_err = 3;


if ($is_err > 0)
{
	$comment_return = "0";
}

else
{
	//����ǻظ������ͬ���ظ����۵�post_id
	if ($to_id > 0)
	{
		$sql = "select post_id from ".$config['tablePre']."comments where id=".$to_id;
		$result = $mysqli->query($sql);
		list($post_id) = $result->fetch_row();	
	}


	//д������
	$sql = "insert into ".$config['tablePre']."comments (post_id,to_id,uid,name,email,content,dateline,ip,is_show) values ($post_id,$to_id,'$uid','$u_name','$email','$content',$dateline,'$ip',$is_show) ";
	$mysqli->query($sql);
	$id = $mysqli->insert_id;
	$comment_return = $id;

	//�������۱��ظ���
	if ($to_id > 0)
	{
		$sql = "update ".$config['tablePre']."comments set replies=replies+1 where id=".$to_id;
		$mysqli->query($sql);
	}
	
	//�������������Ϣ
	if ($post_id > 0)
	{
		$sql = "update ".$config['tablePre']."posts set comments=comments+1 where id=".$post_id;
		$mysqli->query($sql);

		$sql = "select dateline from ".$config['tablePre']."posts where id=".$post_id;
		$result = $mysqli->query($sql);

		//д��̬�ļ�
		if (list($dateline) = $result->fetch_row())
		{
		$writeShowHtml = writeShowHtml($post_id,$dateline);
		}
	}
}
echo $login_return."|".$comment_return;
?>
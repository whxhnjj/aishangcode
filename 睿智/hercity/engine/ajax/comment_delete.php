<?php
require_once("../includes/interface.php");

$comment_id = _POST('comment_id');

$sql = "select uid,to_id,post_id from ".$config['tablePre']."comments where id=".$comment_id;
$result = $mysqli->query($sql);
list($comment_uid,$comment_to_id,$comment_post_id) = $result->fetch_row();


//�ж���û�е�¼
if(!empty($_COOKIE['loftcms_auth'])) {
	list($uid, $u_name) = explode("\t", uc_authcode($_COOKIE['loftcms_auth'], 'DECODE'));

	//�ж�uid�Ƿ�ƥ��
	if ($uid == $comment_uid)
	{
		$sql = "delete from ".$config['tablePre']."comments where id=".$comment_id;
		$result = $mysqli->query($sql);
		
		//�ж�ɾ�������Ƿ�ɹ�
		if ($mysqli->affected_rows > 0)
		{
			//�������۱��ظ���
			if ($comment_to_id > 0)
			{
				$sql = "update ".$config['tablePre']."comments set replies=replies-1 where id=".$comment_to_id;
				$mysqli->query($sql);
			}
			
			//�������±�������
			if ($comment_post_id > 0)
			{
				$sql = "update ".$config['tablePre']."posts set comments=comments-1 where id=".$comment_post_id;
				$mysqli->query($sql);
			}
		
		echo("1");
		}
		else
		{
		echo("-2");
		}
	}
	else
	{
	echo("-1");
	}

} else {
	echo("-1");
}
?>
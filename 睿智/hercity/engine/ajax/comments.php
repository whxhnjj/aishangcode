<?php
require_once("../includes/interface.php");
$is_err = 0;

$uname = _POST('c_uname','S','');
$uid = _POST('c_uid','S','0');


$login_result = 1;
if(!empty($_COOKIE['loftcms_auth'])) {
	list($uid, $uname) = explode("\t", uc_authcode($_COOKIE['loftcms_auth'], 'DECODE'));
	$login_result = 0; 	//已经在登录状态。
}



/*
$uname = _POST('username','S');
$p_word = _POST('password','p');

if(_POST('is_user','S')=='false'){
	$login_result = 1;	//游客评论，无需登录。
}else{

	//会员登录
	if(!empty($_COOKIE['loftcms_auth'])) {
		list($uid, $uname) = explode("\t", uc_authcode($_COOKIE['loftcms_auth'], 'DECODE'));
		$login_result = 0; 	//已经在登录状态。
	} else {
		list($uid, $uname, $password ,$email) = uc_user_login($uname, $p_word);
		if($uid > 0)
		{
			//用户登陆成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
			setcookie('loftcms_auth', uc_authcode($uid."\t".$uname, 'ENCODE'), time()+3600*24);
			//生成同步登录的代码
			$ucsynlogin = uc_user_synlogin($uid);
			$login_result = array('uid'=>$uid,'uname'=>gbkToUtf8($uname),'ucsynlogin'=>$ucsynlogin);
		}
		else
		{
			$login_result = -1;	//登录失败
			$comment_result = -1;
		}
	}

}

if($comment_result != -1) {$comment_result = add_comments($uid,$uname,$email);}
echo (json_encode(array('comment_result'=>$comment_result,'login_result'=>$login_result),true));
*/

$comment_result = add_comments($uid,$uname,'');
echo (json_encode(array('comment_result'=>$comment_result,'login_result'=>$login_result)));



function add_comments($uid,$uname,$email){


	GLOBAL $config;
	GLOBAL $mysqli;

	//获取数据
	$post_id = _POST('post_id','i',0);
	$to_id = _POST('to_id','i',0);
	$dateline = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	$point = _POST('point','i',0);
	$is_show = $config['commentDefaultStatus'];
	$content = _POST('content','S');

	
	
	//处理内容中的话题
	preg_match_all("/#(.{1,20})#/U",$content,$arr_topic);
	while ($topic = current($arr_topic[1]))
	{
		$sql = "update ".$config['tablePre']."topics set comments=comments+1 where title='".$topic."'";
		$result = $mysqli->query($sql);
		
		if ($mysqli->affected_rows == 0) //话题长度不能超20
		{
			$sql = "insert into ".$config['tablePre']."topics (title,comments) values ('".$topic."',1)";
			$mysqli->query($sql);
		}
		next($arr_topic[1]);
	}





	//内容是否为空
	if ($content == '') $is_err = 3;


	if ($is_err > 0)
	{
		$comment_return = -2;
	}

	else
	{
		//如果是回复，则等同被回复评论的post_id
		if ($to_id > 0)
		{
			$sql = "select post_id from ".$config['tablePre']."comments where id=".$to_id;
			$result = $mysqli->query($sql);
			list($post_id) = $result->fetch_row();	
		}


		//写入评论
		$sql = "insert into ".$config['tablePre']."comments (post_id,to_id,uid,name,email,content,dateline,ip,point,replies,is_show,wechat_openid,wechat_headimgurl) values (".$post_id.",".$to_id.",".$uid.",'".$uname."','".$email."','".$content."',".$dateline.",'".$ip."',0,0,".$is_show.",'','') ";

		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		$comment_return = $id;

		//更新评论被回复数
		if ($to_id > 0)
		{
			$sql = "update ".$config['tablePre']."comments set replies=replies+1 where id=".$to_id;
			$mysqli->query($sql);
		}
		
		//更新文章相关信息
		if ($post_id > 0)
		{
			$sql = "update ".$config['tablePre']."posts set comments=comments+1 where id=".$post_id;
			$mysqli->query($sql);

			$sql = "select dateline from ".$config['tablePre']."posts where id=".$post_id;
			$result = $mysqli->query($sql);

			//写静态文件
			if (list($dateline) = $result->fetch_row())
			{
			$writeShowHtml = writeShowHtml($post_id,$dateline);
			}
		}
	}

	return $comment_return;
}


?>
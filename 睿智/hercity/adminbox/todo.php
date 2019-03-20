<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");

//此入id是字符串，是逗号隔开的N个id
$id = _POST('id');
$act = _POST('act');

//比对当前用户的权限与当前模块前置标识，确定是否能访问。
$module_name = substr($act,0,strpos($act,'_'));
if ($_SESSION['usergroup'] >= $config['rights'][$module_name])
{
	switch($act)
	{
	case "posts_del":

	//删除文章
	$sql="delete from ".$config['tablePre']."posts where id in (".$id.")";
	$result = $mysqli->query($sql);
	
	//删除文章content
	$sql="delete from ".$config['tablePre']."postcontents where post_id in (".$id.")";
	$result = $mysqli->query($sql);
	
	//删除文章meta
	$sql="delete from ".$config['tablePre']."postmeta where post_id in (".$id.")";
	$result = $mysqli->query($sql);
	
	//将该文章相关附件设置为游离状态
	$sql="update ".$config['tablePre']."attachments set post_id=0 where post_id in (".$id.")";
	$result = $mysqli->query($sql);
	//将该文章相关评论设置为游离状态
	$sql="update ".$config['tablePre']."comments set post_id=0 where post_id in (".$id.")";
	$result = $mysqli->query($sql);

	$msg = "1";
	break;

	case "posts_show":
	$sql="update ".$config['tablePre']."posts set is_show=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "posts_not_show":
	$sql="update ".$config['tablePre']."posts set is_show=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "posts_good":
	$sql="update ".$config['tablePre']."posts set is_good=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "posts_not_good":
	$sql="update ".$config['tablePre']."posts set is_good=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;


	case "comments_del":
	$sql = "select post_id,to_id from ".$config['tablePre']."comments where id in (".$id.")";
	$result = $mysqli->query($sql);
	while(list($post_id,$to_id) = $result->fetch_row())
	{
		if ($post_id > 0)
		{
		$sql = "update ".$config['tablePre']."posts set comments=comments-1 where id=".$post_id;
		$mysqli->query($sql);
		}
		if ($to_id > 0)
		{
		$sql = "update ".$config['tablePre']."comments set replies=replies-1 where id=".$to_id;
		$mysqli->query($sql);
		}
	}
	$sql="delete from ".$config['tablePre']."comments where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "comments_show":
	$sql="update ".$config['tablePre']."comments set is_show=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "comments_not_show":
	$sql="update ".$config['tablePre']."comments set is_show=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;


	case "users_del":
	$sql="delete from ".$config['tablePre']."users where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "users_active":
	$sql="update ".$config['tablePre']."users set is_active=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "users_not_active":
	$sql="update ".$config['tablePre']."users set is_active=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "logs_del":
	$sql="delete from ".$config['tablePre']."logs where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "kinds_del":
	$sql="delete from ".$config['tablePre']."kinds where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "kinds_list":
	$sql="update ".$config['tablePre']."kinds set is_list = 1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "kinds_not_list":
	$sql="update ".$config['tablePre']."kinds set is_list = 0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "attachments_del":

	$sql="select filename from ".$config['tablePre']."attachments where id in (".$id.")";
	$result = $mysqli->query($sql);
	while(list($filename) = $result->fetch_row())
	{
		if ($filename)
		{
		$documentRoot = $_SERVER['DOCUMENT_ROOT'];
		$targetPath = $config['basePath'].$config['attachmentPath'];
		$targetfile = $documentRoot.$targetPath.$filename;
		if (file_exists($targetfile)) @unlink($targetfile);
		if (file_exists($targetfile.'.thumb.jpg')) @unlink($targetfile.'.thumb.jpg');
		}
	}

	$sql="delete from ".$config['tablePre']."attachments where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;


	case "poll_subjects_del":
	$sql="delete from ".$config['tablePre']."poll_subjects where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "poll_options_del":
	$sql="delete from ".$config['tablePre']."poll_options where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "poll_options_show":
	$sql="update ".$config['tablePre']."poll_options set is_show=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "poll_options_not_show":
	$sql="update ".$config['tablePre']."poll_options set is_show=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;
	
	case "links_del":
	$sql="delete from ".$config['tablePre']."links where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;
	
	case "persons_del":
	$sql="delete from ".$config['tablePre']."persons where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;
	
	case "persons_show":
	$sql="update ".$config['tablePre']."persons set is_show=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "persons_not_show":
	$sql="update ".$config['tablePre']."persons set is_show=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "brands_del":
	$sql="delete from ".$config['tablePre']."brands where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;
	
	case "brands_show":
	$sql="update ".$config['tablePre']."brands set is_show=1 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;

	case "brands_not_show":
	$sql="update ".$config['tablePre']."brands set is_show=0 where id in (".$id.")";
	$result = $mysqli->query($sql);
	$msg = "1";
	break;



	}
		
}
else
{
$msg = "0";
}

echo $msg;
?>
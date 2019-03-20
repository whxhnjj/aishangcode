<?php
require_once('ini.php');

$id = $_GET['id'];

$post = getPostDetail($id);

if (isset($_SESSION['post_hits_'.$id]))
{
	$post['hits'] = $_SESSION['post_hits_'.$id];
}
else
{
	$_SESSION['post_hits_'.$id] = $post['hits'];
}



$kind = kindsGetInfo($post['kind_id']);
$kind['name'] = gbkToUtf8($kind['name']);

$comments = getCommentList(10,1,$id);


$smarty->assign("post",$post);
$smarty->assign("kind",$kind);
$smarty->assign("comments",$comments);
$smarty->display($post['template']);
?>
<?php
require_once("../includes/interface.php");

$id = _POST('id','i');
//获取文章的人气，喜欢，评论数。
$sql = "select hits,point,comments from ".$config['tablePre']."posts where id=".$id;
$result = $mysqli->query($sql);
$row = $result->fetch_array();
echo json_encode($row);



//hits+1
$i = rand(1,5);
session_start();
$session_tag = isset($_SESSION["posthits".$id]) ? $_SESSION["posthits".$id] : 0;
if (time() - $session_tag > 60)
{
$sql = "update ".$config['tablePre']."posts set hits=hits+".$i." where id=".$id;
$mysqli->query($sql);
$_SESSION["posthits".$id] = time();
}
?>
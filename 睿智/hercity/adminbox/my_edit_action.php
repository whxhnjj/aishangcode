<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');

$sql = "select pword,dateline from ".$config['tablePre']."users where id=".$id;
$result = $mysqli->query($sql) or die("fail");
list($pword,$dateline)=$result->fetch_row() or die("fail");

$password = _POST('password');
$nickname = _POST('nickname');
$email = _POST('email');

if (trim($password)) $pword = md5(md5($password).$dateline);

$sql = "update ".$config['tablePre']."users set pword='".$pword."',nickname='".$nickname."',email='".$email."' where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("个人资料修改成功",$_SERVER["HTTP_REFERER"]);
require_once("includes/footer.php");
?>
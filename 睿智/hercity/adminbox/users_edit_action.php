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

$usergroup = _POST('usergroup','i');
$username = _POST('username');
$password = _POST('password');
$nickname = _POST('nickname');
$email = _POST('email');

if (trim($password)) $pword = md5(md5($password).$dateline);

$sql = "update ".$config['tablePre']."users set usergroup=".$usergroup.",uname='".$username."',pword='".$pword."',nickname='".$nickname."',email='".$email."' where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("用户修改成功",$url);
require_once("includes/footer.php");
?>
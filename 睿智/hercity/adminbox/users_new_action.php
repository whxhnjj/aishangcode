<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$dateline = time();
$usergroup = _POST('usergroup','i');
$username = _POST('username');
$password = _POST('password');
$nickname = _POST('nickname');
$email = _POST('email');

$pword = md5(md5($password).$dateline);

$sql = "insert into ".$config['tablePre']."users (usergroup,uname,pword,nickname,email,dateline) values (".$usergroup.",'".$username."','".$pword."','".$nickname."','".$email."',".$dateline.")";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("用户新建成功",$url);
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

session_start();
$_SESSION['user_agent'] = md5($_SERVER['HTTP_USER_AGENT'].SESSION_TAIL);
$username = _POST('username','p');
$password = _POST('password','p');

//��ȡ�û�ע��ʱ��
$sql = "select dateline from ".$config['tablePre']."users where uname='$username' limit 1";
$result = $mysqli->query($sql);
list($dateline) = $result->fetch_row();
$pword = md5(md5($password).$dateline);

$sql = "select id,uname,usergroup,modeflag from ".$config['tablePre']."users where uname='$username' and pword='$pword' and is_active ";
$result = $mysqli->query($sql);


//д��־
$ip = (isset($_SERVER["HTTP_VIA"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
$sys = $_SERVER["HTTP_USER_AGENT"];
$dateline = time();

if ($result->num_rows >= 1)
{
list($userid,$username,$usergroup,$modeflag) = $result->fetch_row();
$_SESSION['userid'] = $userid;
$_SESSION['username'] = $username;
$_SESSION['usergroup'] = $usergroup;
$_SESSION['userlogintime'] = $dateline;

//���µ�¼����
$sql = "update ".$config['tablePre']."users set logintimes=logintimes+1 where uname='$username'";
$mysqli->query($sql);

//д���¼�ɹ���־
$sql = "insert into ".$config['tablePre']."logs (ip,sys,dateline,uname,pword,is_success) values ('".$ip."','".$sys."',".$dateline.",'".$username."','".$pword."',1)";
$mysqli->query($sql);
header("Location:index.php");
}
else
{
//д���¼ʧ����־
$sql = "insert into ".$config['tablePre']."logs (ip,sys,dateline,uname,pword,is_success) values ('".$ip."','".$sys."',".$dateline.",'".$username."','".$pword."',0)";
$mysqli->query($sql);
header("Location:admin_login.php?err=1");
}

require_once("includes/footer.php");
?>
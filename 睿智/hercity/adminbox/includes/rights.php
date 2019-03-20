<?php
session_start();


//取得该模块的前置标识
$script_name = $_SERVER['SCRIPT_NAME'];
$admin_path =  substr($script_name,0,strpos($script_name,'/',1)+1);
$script_name = substr($script_name,strrpos($script_name,'/')+1);
$module_name = substr($script_name,0,strpos($script_name,'_'));


if (!isset($_SESSION['username']) || $_SESSION['user_agent'] !== md5($_SERVER['HTTP_USER_AGENT'].SESSION_TAIL))
{
header("location:".$admin_path."admin_login.php?err=2");
}


//比对当前用户的权限与当前模块前置标识，确定是否能访问。
if ($module_name)
{
	if ($_SESSION['usergroup'] < $config['rights'][$module_name])
	{
		header("location:".$admin_path."admin_login.php?err=3");
	}
}
?>
<?php
header("Content-type:text/html;charset=gbk");  
ini_set("session.cookie_httponly", 1);

define('SESSION_TAIL','F5Y7U8K2');

//软件信息
$config['softVersion']="loftCMS 1.5.3";
$config['softAuthor']="muzili.cn";
$config['softUpdate']="2016-10-18";

//引入站点配置文件
require_once(dirname(__FILE__)."/../../config/config.php");
?>
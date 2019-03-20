<?php
require_once("includes/config.php");
require_once("includes/rights.php");
?>
<!--This is IE DTD patch , Don't delete this line.-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>loftCMS</title>
<link href="assets/css/frame.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="assets/js/frame.js"></script>
</head>
<body class="showmenu">

<!--header_s-->
<div id="header">
	<div id="logo">loftCMS</div>
	<div id="welcome">您好：<?=$_SESSION['username']?> ，欢迎使用loftCMS！<a href="my_edit.php" target="main">[修改个人资料]</a> <a href="admin_logout.php" target="_top">[注销]</a></div>
	<div id="nav">
		<a href="#" onclick="JumpFrame('index_menu.php','index_body.php');">系统主页</a> |
		<a href="../" target="_blank">网站主页</a> |
		<a href="http://www.loftcms.com" target="_blank">在线帮助</a> |
		<a href="#" id="togglemenu">隐藏菜单</a> |
		<a href="#" class="ac_qucikmenu" id="ac_qucikmenu">快捷方式</a>
	</div>

</div>
<!--header_e-->


<!--left_s-->
<div class="left">
	<div class="menu">
		 <iframe src="index_menu.php" id="menufra" name="menu" frameborder="0"></iframe>
	</div>
</div>
<!--left_e-->

<!--right_s-->
<div class="right">
	<div class="main">
		<iframe id="main" name="main" frameborder="0" src="index_body.php"></iframe>
	</div>
</div>
<!--right_e-->

<!--qucikmenu_s-->
<div class="qucikmenu" id="qucikmenu">
<ul>
<li><a href="posts_new.php" target="main">内容发布</a></li>
<li><a href="comments_index.php" target="main">评论管理</a></li>
<li><a href="crons_write_index.php" target="main">更新html</a></li>
<li><a href="kinds_index.php" target="main">栏目管理</a></li>
<li><a href="/engine/" target="_blank">预览首页</a></li>
</ul>
</div>
<!--qucikmenu_e-->


</body>
</html>
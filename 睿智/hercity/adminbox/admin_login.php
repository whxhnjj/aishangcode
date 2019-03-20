<?php
require_once("includes/config.php");
require_once("includes/header.php");
?>

<script type="text/javascript">

$(function()
{
$("#username").focus();
$(".textbox").focus(
	function(){
		$(this).addClass("focus");
    });
	
$(".textbox").blur(
	function(){
		$(this).removeClass("focus");
    });	
	
$("body").css("margin","0px"); 
	
});

</script>


<div class="login_message"><a href="../"><-返回网站首页</a></div>

<div class="login" >
<img src="assets/images/logo.gif" alt="loftCMS" />
<form name="form1" action="admin_login_action.php" method="post" target="_top">
<label for="username">用户名：</label><br /><input type="text" name="username" id="username" maxlength="20" class="textbox" /><br />
<label for="password">密码：</label><br /><input type="password" name="password" id="password" maxlength="20" class="textbox" />
<input type="submit" value="登录" class="button" />
<?php
$err = isset($_GET['err'])?$_GET['err']:"";
switch($err)
{
	case "1":
		echo ("用户名或密码错误。");
		break;
	case "2":
		echo ("您还没有登录或已超时。");
		break;
	case "3":
		echo ("您没有足够的访问权限。");
		break;
}
?>
</form>

当前系统时间：<?=date("H:i:s")?>  <?=$config['siteName']?>
</div>

<?php
require_once("includes/footer.php");
?>
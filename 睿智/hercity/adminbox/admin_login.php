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


<div class="login_message"><a href="../"><-������վ��ҳ</a></div>

<div class="login" >
<img src="assets/images/logo.gif" alt="loftCMS" />
<form name="form1" action="admin_login_action.php" method="post" target="_top">
<label for="username">�û�����</label><br /><input type="text" name="username" id="username" maxlength="20" class="textbox" /><br />
<label for="password">���룺</label><br /><input type="password" name="password" id="password" maxlength="20" class="textbox" />
<input type="submit" value="��¼" class="button" />
<?php
$err = isset($_GET['err'])?$_GET['err']:"";
switch($err)
{
	case "1":
		echo ("�û������������");
		break;
	case "2":
		echo ("����û�е�¼���ѳ�ʱ��");
		break;
	case "3":
		echo ("��û���㹻�ķ���Ȩ�ޡ�");
		break;
}
?>
</form>

��ǰϵͳʱ�䣺<?=date("H:i:s")?>  <?=$config['siteName']?>
</div>

<?php
require_once("includes/footer.php");
?>
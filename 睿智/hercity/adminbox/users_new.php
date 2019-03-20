<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$usergroup = _GET('usergroup','i');
?>

<script type="text/javascript">
<!--
$(function(){

//表单验证
$("#form1").submit( function () {
	
	if ($("#usergroup").val() == '0')
	{
	alert("请选择用户组！");
	$("#usergroup").focus();
	return false;
	}
	
	if ($("#username").val() == '')
	{
	alert("用户名不能为空！");
	$("#username").focus();
	return false;
	}
	
	if ($("#password").val() == '')
	{
	alert("密码不能为空！");
	$("#password").focus();
	return false;
	}

  return true;
}); 

});

//-->
</script>



<div id="path">
loftCMS -> 用户管理 -> 新建
</div>

<form name="form1" id="form1" action="users_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>用户组：</th>
<td>
<select name="usergroup" id="usergroup" size="1">
<option value="0" class="inactive">选择用户组</option>
<option value="1" <?php if($usergroup == 1) echo("selected=\"selected\" ")?>>投稿者</option>
<option value="2" <?php if($usergroup == 2) echo("selected=\"selected\" ")?>>作者</option>
<option value="3" <?php if($usergroup == 3) echo("selected=\"selected\" ")?>>编辑</option>
<option value="4" <?php if($usergroup == 4) echo("selected=\"selected\" ")?>>主编</option>
<option value="5" <?php if($usergroup == 5) echo("selected=\"selected\" ")?>>管理员</option>
</select>
</td>
</tr>
<tr>
<th>用户名：</th>
<td><input type="text" name="username" id="username" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>密码：</th>
<td><input type="text" name="password" id="password" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>昵称：</th>
<td><input type="text" name="nickname" id="nickname" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>E-mail：</th>
<td><input type="text" name="email" id="email" size="50" maxlength="80" /></td>
</tr>
<tr>
<td colspan="2" class="buttonbox">
<input type="submit" name="ok" id="ok_back" value="确定" accesskey="s" />
<input type="button" name="cancel" id="cancel" value="取消" onClick="history.back(1)" />
</td>
</tr>
</table>

</form>



<?php
require_once("includes/footer.php");
?>
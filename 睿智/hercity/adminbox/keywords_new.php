<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>

<script type="text/javascript">
<!--
$(function(){

//����֤
$("#form1").submit( function () {

	if ($("#name").val() == '')
	{
	alert("�ؼ��ʲ���Ϊ�գ�");
	$("#name").focus();
	return false;
	}
  return true;
}); 

});

//-->
</script>

<div id="path">
loftCMS -> �ؼ��ʹ��� -> �½�
</div>

<form name="form1" id="form1" action="keywords_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />

<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<tr>
<th>��ǩ��</th>
<td><input type="text" name="name" id="name" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>Ӣ������</th>
<td><input type="text" name="ename" id="ename" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="80" maxlength="100" /></td>
</tr>
<tr>
<th>������</th>
<td><textarea name="description" id="description" /></textarea></td>
</tr>
<tr>
<th>���ӵ���</th>
<td><textarea name="linkto" id="linkto" /></textarea></td>
</tr>
<tr>
<td colspan="2" class="buttonbox">
<input type="submit" name="ok" id="ok_back" value="ȷ��" accesskey="s" />
<input type="button" name="cancel" id="cancel" value="ȡ��" onClick="history.back(1)" />
</td>
</tr>
</table>

</form>


<?php
require_once("includes/footer.php");
?>
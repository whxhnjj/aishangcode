<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _GET('id','i');

$sql = "select title,description from ".$config['tablePre']."attachments where id=".$id;
$result = $mysqli->query($sql) or die("fail");
list($title,$description)=$result->fetch_row() or die("fail");
$result->free();
?>

<script type="text/javascript">
<!--
$(function(){

//表单验证
$("#form1").submit( function () {

	if ($("#title").val() == '')
	{
	alert("附件标题不能为空！");
	$("#title").focus();
	return false;
	}
	
  return true;
}); 

});

//-->
</script>



<div id="path">
loftCMS -> 附件管理 -> 修改
</div>

<form name="form1" id="form1" action="attachments_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>标题：</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" value="<?=$title?>" /></td>
</tr>
<tr>
<th>描述：</th>
<td><textarea name="description" id="description" ><?=$description?></textarea></td>
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
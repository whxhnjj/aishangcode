<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$parent_id = isset($_GET["parent_id"])?$_GET["parent_id"]:0;
?>

<script type="text/javascript">
<!--
$(function(){

//表单验证
$("#form1").submit( function () {

	if ($("#name").val() == '')
	{
	alert("栏目名不能为空！");
	$("#name").focus();
	return false;
	}
	
  return true;
}); 

});

//-->
</script>



<div id="path">
loftCMS -> 栏目管理 -> 新建
</div>

<form name="form1" id="form1" action="kinds_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="parent_id" id="parent_id" value="<?=$parent_id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>栏目名：</th>
<td><input type="text" name="name" id="name" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>栏目标题：</th>
<td><input type="text" name="title" id="title" size="50" maxlength="255" /></td>
</tr>
<tr>
<th>栏目关键词：</th>
<td><input type="text" name="keywords" id="keywords" size="50" maxlength="255" /></td>
</tr>
<tr>
<th>栏目描述：</th>
<td><textarea name="description" id="description"></textarea></td>
</tr>
<tr>
<th>生成列表页：</th>
<td>
<input name="is_list" id="is_list1" type="radio" value="1" /><label for="is_list1">是</label> 
<input name="is_list" id="is_list0" type="radio" value="0" /><label for="is_list0">否</label>  
</td>
</tr>
<tr>
<th>栏目文件夹：</th>
<td><input type="text" name="folder" id="folder" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>分页容量：</th>
<td><input type="text" name="pagesize" id="pagesize" size="4" maxlength="4" /></td>
</tr>
<tr>
<th>栏目模板：</th>
<td>
<select name="template" id="template" size="1">
<option value="" class="inactive">选择栏目模板</option>
<?php
arrayToOptions($config['listTemplates'],1,1);
?>
</select>
</td>
</tr>
<tr>
<th>文章模板：</th>
<td>
<select name="showtemplate" id="showtemplate" size="1">
<option value="" class="inactive">选择文章模板</option>
<?php
arrayToOptions($config['showTemplates'],1,1);
?>
</select>
<span>指定本栏目下文章的默认模板</span>
</td>
</tr>
<tr>
<th>链接到：</th>
<td><textarea name="linkto" id="linkto"></textarea></td>
</tr>
<tr>
<th>栏目备注：</th>
<td><textarea name="remark" id="remark"></textarea></td>
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
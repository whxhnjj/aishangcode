<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
$cat = _GET('cat','i');
?>

<script type="text/javascript">
<!--
$(function(){
	//表单验证
	$("#form1").submit( function () {
		if ($("#theletter").val() == '')
		{
		$("#theletter").next("span").html('<span class="red">注意：首字母不能为空。</span>');
		$("#theletter").focus();
		return false;
		}
		if ($("#name").val() == '')
		{
		$("#name").next("span").html('<span class="red">注意：名称不能为空。</span>');
		$("#name").focus();
		return false;
		}
		if ($("#profile").val() == '')
		{
		$("#profile").next("span").html('<span class="red">注意：简介不能为空。</span>');
		$("#profile").focus();
		return false;
		}

		//判断姓名与英文名是否有重复。
		is_submit = true;
		$.ajax({type: "POST", url: "ajax/persons_samename.php", data: { name:$("#name").val(), ename:$("#ename").val(), id:$("#id").val() }, async: false, timeout: 10000, cache:false, success: function(data) {
			switch (data)
			{
			case "1":
				$("#name").next("span").html('<span class="red">注意：有重复名称。</span>');
				$("#name").focus();
				is_submit = false;
				break;
			case "2":
				$("#ename").next("span").html('<span class="red">注意：有重复英文名。</span>');
				$("#ename").focus();
				is_submit = false;
				break;
			case "3":	
				$("#name").next("span").html('<span class="red">注意：有重复名称。</span>');
				$("#ename").next("span").html('<span class="red">注意：有重复英文名。</span>');
				$("#name").focus();
				is_submit = false;
				break;
			}
		}});
		return is_submit;
	});

	//实时验证
	$("#name").blur(function(){
		$.ajax({type: "POST", url: "ajax/persons_samename.php", data: { name:$("#name").val(), id:$("#id").val() }, timeout: 10000, cache:false, success: function(data) {
			if (data == "1")
			{
				$("#name").next("span").html('<span class="red">注意：有重复名称。</span>');
			}
			else
			{
				$("#name").next("span").html('<span class="green">可以录入。</span>');
			}
		}});
	});

	$("#ename").blur(function(){
		$.ajax({type: "POST", url: "ajax/persons_samename.php", data: { ename:$("#ename").val(), id:$("#id").val() }, timeout: 10000, cache:false, success: function(data) {
			if (data == "2")
			{
				$("#ename").next("span").html('<span class="red">注意：有重复英文名。</span>');
			}
			else
			{
				$("#ename").next("span").html('<span class="green">可以录入。</span>');
			}
		}});
	});

});

//-->
</script>

<div id="path">
loftCMS -> 人物管理 -> 新建
</div>

<form name="form1" id="form1" action="persons_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>类型：</th>
<td>
<select name="cat" id="cat">
<?php
arrayToOptions($config['personCats'],0,1,$cat);
?>
</select>
</td>
</tr>
<tr>
<th>首字母：</th>
<td>
<select name="theletter" id="theletter">
<option value="">请选择</option>
<?php
for ($i=65;$i<91;$i++){$arrayLetter[chr($i)] = chr($i);}
$arrayLetter[0] = '其它';
arrayToOptions($arrayLetter,0,1);
?>
</select> <span></span>
</td>
</tr>
<tr>
<th>标签：</th>
<td>
<?php
$i = 0;
foreach($config['personTags'] as $tagline)
{
$i++;
arrayToCheckbox('tags',$tagline,1,1,'',0,$i);
echo('<hr />');
}
?>
</td>
</tr>
<tr>
<th>名称：</th>
<td><input type="text" name="name" id="name" size="40" maxlength="50" /> <span></span></td>
</tr>
<tr>
<th>别名：</th>
<td><input type="text" name="oname" id="oname" size="80" maxlength="80" /> <span>多个别名请用逗号隔开。</span></td>
</tr>
<tr>
<th>英文名：</th>
<td><input type="text" name="ename" id="ename" size="30" maxlength="50" /> <span>姓名全拼，纯小写字母组成。或有重复可加数字。</span></td>
</tr>
<tr>
<th>性别：</th>
<td>
<select name="gender" id="gender">
<?php
$arrayGender[0] = '女';
$arrayGender[1] = '男';
arrayToOptions($arrayGender,0,1);
?>
</select>
</td>
</tr>
<tr>
<th>简介：</th>
<td><textarea name="profile" id="profile"></textarea> <span></span></td>
</tr>
<tr>
<th>档案：</th>
<td><textarea name="archives" id="archives"></textarea></td>
</tr>
<tr>
<th>网站：</th>
<td><input type="text" name="website" id="website" size="120" maxlength="255" /></td>
</tr>
<tr>
<th>微博：</th>
<td><input type="text" name="weibo" id="weibo" size="120" maxlength="255" /></td>
</tr>

<tr>
<th>缩略图1：</th>
<td>
<input type="text" name="thumb1" id="thumb1" size="50" maxlength="100" />
<input class="img_browse" id="thumb1_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>缩略图2：</th>
<td>
<input type="text" name="thumb2" id="thumb2" size="50" maxlength="100" />
<input class="img_browse" id="thumb2_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>

<tr>
<th>标题：</th>
<td><input type="text" name="title" id="title" size="80" maxlength="255" /></td>
</tr>
<tr>
<th>关键词：</th>
<td><input type="text" name="keywords" id="keywords" size="80" maxlength="255" /></td>
</tr>
<tr>
<th>描述：</th>
<td><textarea name="description" id="description"></textarea></td>
</tr>
<tr>
<th>保存状态：</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" /><label for="is_show">草稿</label></td>
</tr>
<tr><td colspan="2" class="td_blank"></td></tr>
</table>

<div class="fixed_buttonbox">
<input type="submit" name="ok" id="ok_back" value="保存并返回" accesskey="s" />
<input type="button" name="cancel" id="cancel" value="取消(×)" onClick="if (confirm('确定要放弃吗？')){history.back(1)}" />
</div>

</form>


<?php
require_once("includes/footer.php");
?>
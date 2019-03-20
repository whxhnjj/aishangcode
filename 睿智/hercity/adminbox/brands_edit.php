<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
$id = _GET('id','i');
$sql = "select * from ".$config['tablePre']."brands where id=".$id;
$result = $mysqli->query($sql) or die("查询失败");
$brand = $result->fetch_array() or die("fail");
$result->free();
?>

<script type="text/javascript" src="libraries/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
$(function(){


	//ckeditor 设置代码换行等
	CKEDITOR.on( 'instanceReady' , function( ev ){
	
		var editor = ev.editor,
			dataProcessor = editor.dataProcessor,
			htmlFilter = dataProcessor && dataProcessor.htmlFilter;
	 
		dataProcessor.writer.selfClosingEnd = ' />';
		dataProcessor.writer.lineBreakChars = '\n';
	 
		// Make output formatting behave similar to FCKeditor
		var dtd = CKEDITOR.dtd;
		for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) )
		{
			dataProcessor.writer.setRules( e,
				{
					indent : false,
					breakBeforeOpen : true,
					breakAfterOpen : false,
					breakBeforeClose : false,
					breakAfterClose : true
				});
		}
	});


	//表单验证
	$("#form1").submit( function () {
		if ($("#firstname").val() == '')
		{
		$("#firstname").next("span").html('<span class="red">注意：品牌名称不能为空。</span>');
		$("#firstname").focus();
		return false;
		}
		if ($("#domain").val() == '')
		{
		$("#domain").next("span").html('<span class="red">注意：域名不能为空。</span>');
		$("#domain").focus();
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
		$.ajax({type: "POST", url: "ajax/brands_samefirstname.php", data: { firstname:$("#firstname").val(), id:$("#id").val() }, async: false, timeout: 10000, cache:false, success: function(data) {
			if (data == "1")
			{
				$("#firstname").next("span").html('<span class="red">注意：有重复品牌名称。</span>');
				$("#firstname").focus();
				is_submit = false;
			}
		}});
		return is_submit;
	});

	//实时验证
	$("#firstname").blur(function(){

		$.ajax({type: "POST", url: "ajax/brands_samefirstname.php", data: { firstname:$("#firstname").val(), id:$("#id").val() }, timeout: 10000, cache:false, success: function(data) {
			if (data == "1")
			{
				$("#firstname").next("span").html('<span class="red">注意：有重复名称。</span>');
			}
			else
			{
				$("#firstname").next("span").html('<span class="green">可以录入。</span>');
			}
		}});
	});
});

//-->
</script>

<div id="path">
loftCMS -> 品牌管理 -> 新建
</div>

<form name="form1" id="form1" action="brands_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>标签：</th>
<td>
<?php
$i = 0;
foreach($config['brandTags'] as $tagline)
{
$i++;
arrayToCheckbox('tags',$tagline,1,1,$brand['tags'],1,$i);
echo('<hr />');
}
?>
</td>
</tr>
<tr>
<th>品牌名称：</th>
<td><input type="text" name="firstname" id="firstname" size="20" maxlength="20" value="<?=$brand['firstname']?>" /> <span>品牌词。如“可遇”</span></td>
</tr>
<tr>
<th>行业名称：</th>
<td><input type="text" name="lastname" id="lastname" size="20" maxlength="20" value="<?=$brand['lastname']?>" /> <span>行业词。可与品牌词相连为一个名称。如“笔记本”</span></td>
</tr>
<tr>
<th>英文名称：</th>
<td><input type="text" name="ename" id="ename" size="30" maxlength="50" value="<?=$brand['ename']?>" /> <span>品牌的英文名称。如“Oncemeet”</span></td>
</tr>
<tr>
<th>域名：</th>
<td><input type="text" name="domain" id="domain" size="20" maxlength="20" value="<?=$brand['domain']?>" /> <span>品牌英文名或拼音组成，纯小写字母，不含空格等特殊字符。如“oncemeet”</span></td>
</tr>
<tr>
<th>标题：</th>
<td><input type="text" name="title" id="title" size="50" maxlength="100" value="<?=$brand['title']?>" /> <span>品牌页面标题</span></td>
</tr>
<tr>
<th>是否认证：</th>
<td>
<select name="is_auth" id="is_auth">
<?php
$arrayAuth[0] = '未认证';
$arrayAuth[1] = '已认证';
arrayToOptions($arrayAuth,0,1,$brand['is_auth']);
?>
</select>
</td>
</tr>
<tr>
<th>认证文字：</th>
<td><input type="text" name="auth_info" id="auth_info" size="100" maxlength="200" value="<?=$brand['auth_info']?>" /> <span></span></td>
</tr>
<tr>
<th>简介：</th>
<td><textarea name="profile" id="profile" class="ckeditor"><?=$brand['profile']?></textarea> <span></span></td>
</tr>
<tr>
<th>总部所在地：</th>
<td><input type="text" name="location" id="location" size="20" maxlength="20" value="<?=$brand['location']?>" /></td>
</tr>
<tr>
<th>地址：</th>
<td><input type="text" name="address" id="address" size="100" maxlength="200" value="<?=$brand['address']?>" /></td>
</tr>
<tr>
<th>电话：</th>
<td><input type="text" name="tel" id="tel" size="20" maxlength="50" value="<?=$brand['tel']?>" /></td>
</tr>
<tr>
<th>传真：</th>
<td><input type="text" name="fax" id="fax" size="20" maxlength="50" value="<?=$brand['fax']?>" /></td>
</tr>
<tr>
<th>电邮：</th>
<td><input type="text" name="email" id="email" size="20" maxlength="50" value="<?=$brand['email']?>" /></td>
</tr>
<tr>
<th>QQ：</th>
<td><input type="text" name="qq" id="qq" size="20" maxlength="50" value="<?=$brand['qq']?>" /></td>
</tr>
<tr>
<th>网站：</th>
<td><input type="text" name="website" id="website" size="120" maxlength="255" value="<?=$brand['website']?>" /></td>
</tr>
<tr>
<th>网店：</th>
<td><input type="text" name="webshop" id="webshop" size="120" maxlength="255" value="<?=$brand['webshop']?>" /></td>
</tr>
<tr>
<th>微博：</th>
<td><input type="text" name="weibo" id="weibo" size="120" maxlength="255" value="<?=$brand['weibo']?>" /></td>
</tr>
<tr>
<th>微信：</th>
<td><input type="text" name="weixin" id="weixin" size="120" maxlength="255" value="<?=$brand['weixin']?>" /></td>
</tr>
<tr>
<th>LOGO：</th>
<td>
<input type="text" name="logo" id="logo" size="50" maxlength="100" value="<?=$brand['logo']?>" />
<input class="img_browse" id="logo_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>主图：</th>
<td>
<input type="text" name="pic" id="pic" size="50" maxlength="100" value="<?=$brand['pic']?>" />
<input class="img_browse" id="pic_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>加盟代理：</th>
<td>
<select name="is_allow_join" id="is_allow_join">
<?php
$arrayJOIN['0'] = '线下加盟代理';
$arrayJOIN['-1'] = '不允许';
$arrayJOIN['1'] = '允许';
arrayToOptions($arrayJOIN,0,1,$brand['is_allow_join']);
?>
</select>

<select name="is_allow_agent" id="is_allow_agent">
<?php
$arrayAGENT['0'] = '网上代理';
$arrayAGENT['-1'] = '不允许';
$arrayAGENT['1'] = '允许';
arrayToOptions($arrayAGENT,0,1,$brand['is_allow_agent']);
?>
</select>
</td>
</tr>
<tr>
<th>关注人次：</th>
<td>
<input type="text" name="hits" id="hits" size="7" value="<?=$brand['hits']?>" />
<span></span>
</td>
</tr>
<tr>
<th>热度指数：</th>
<td>
<input type="text" name="hots" id="hots" size="7" value="<?=$brand['hots']?>" />
<span></span>
</td>
</tr>
<tr>
<th>经营天数：</th>
<td>
<input type="text" name="days" id="days" size="7" value="<?=$brand['days']?>" />
<span></span>
</td>
</tr>
<tr>
<th>排序权值：</th>
<td>
<input type="text" name="order_id" id="order_id" size="5" value="<?=$brand['order_id']?>" />
<span>可以输入任何整数。权值越大，排序越靠前。</span>
</td>
</tr>
<tr>
<th>保存状态：</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" <?php if(!$brand['is_show']) echo('checked="checked"');?> /><label for="is_show">草稿</label></td>
</tr>
<tr>
<th>备注：</th>
<td><textarea name="remark" id="remark"><?=$brand['remark']?></textarea></td>
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
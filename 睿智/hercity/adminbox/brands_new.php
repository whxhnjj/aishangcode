<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>
<script type="text/javascript" src="libraries/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
<!--
$(function(){


	//ckeditor ���ô��뻻�е�
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




	//����֤
	$("#form1").submit( function () {
		if ($("#firstname").val() == '')
		{
		$("#firstname").next("span").html('<span class="red">ע�⣺Ʒ�����Ʋ���Ϊ�ա�</span>');
		$("#firstname").focus();
		return false;
		}
		if ($("#domain").val() == '')
		{
		$("#domain").next("span").html('<span class="red">ע�⣺��������Ϊ�ա�</span>');
		$("#domain").focus();
		return false;
		}
		if ($("#profile").val() == '')
		{
		$("#profile").next("span").html('<span class="red">ע�⣺��鲻��Ϊ�ա�</span>');
		$("#profile").focus();
		return false;
		}

		//�ж�������Ӣ�����Ƿ����ظ���
		is_submit = true;
		$.ajax({type: "POST", url: "ajax/brands_samefirstname.php", data: { firstname:$("#firstname").val(), id:$("#id").val() }, async: false, timeout: 10000, cache:false, success: function(data) {
			if (data == "1")
			{
				$("#firstname").next("span").html('<span class="red">ע�⣺���ظ�Ʒ�����ơ�</span>');
				$("#firstname").focus();
				is_submit = false;
			}
		}});
		return is_submit;
	});

	//ʵʱ��֤
	$("#firstname").blur(function(){
		$.ajax({type: "POST", url: "ajax/brands_samefirstname.php", data: { firstname:$("#firstname").val(), id:$("#id").val() }, timeout: 10000, cache:false, success: function(data) {
			if (data == "1")
			{
				$("#firstname").next("span").html('<span class="red">ע�⣺���ظ����ơ�</span>');
			}
			else
			{
				$("#firstname").next("span").html('<span class="green">����¼�롣</span>');
			}
		}});
	});
});

//-->
</script>

<div id="path">
loftCMS -> Ʒ�ƹ��� -> �½�
</div>

<form name="form1" id="form1" action="brands_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>��ǩ��</th>
<td>
<?php
$i = 0;
foreach($config['brandTags'] as $tagline)
{
$i++;
arrayToCheckbox('tags',$tagline,1,1,'',0,$i);
echo('<hr />');
}
?>
</td>
</tr>
<tr>
<th>Ʒ�����ƣ�</th>
<td><input type="text" name="firstname" id="firstname" size="20" maxlength="20" /> <span>Ʒ�ƴʡ��硰������</span></td>
</tr>
<tr>
<th>��ҵ���ƣ�</th>
<td><input type="text" name="lastname" id="lastname" size="20" maxlength="20" /> <span>��ҵ�ʡ�����Ʒ�ƴ�����Ϊһ�����ơ��硰�ʼǱ���</span></td>
</tr>
<tr>
<th>Ӣ�����ƣ�</th>
<td><input type="text" name="ename" id="ename" size="30" maxlength="50" /> <span>Ʒ�Ƶ�Ӣ�����ơ��硰Oncemeet��</span></td>
</tr>
<tr>
<th>������</th>
<td><input type="text" name="domain" id="domain" size="20" maxlength="20" /> <span>Ʒ��Ӣ������ƴ����ɣ���Сд��ĸ�������ո�������ַ����硰oncemeet��</span></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="100" /> <span>Ʒ��ҳ�����</span></td>
</tr>
<tr>
<th>�Ƿ���֤��</th>
<td>
<select name="is_auth" id="is_auth">
<?php
$arrayAuth[0] = 'δ��֤';
$arrayAuth[1] = '����֤';
arrayToOptions($arrayAuth,0,1);
?>
</select>
</td>
</tr>
<tr>
<th>��֤���֣�</th>
<td><input type="text" name="auth_info" id="auth_info" size="100" maxlength="200" /> <span></span></td>
</tr>
<tr>
<th>��飺</th>
<td><textarea name="profile" id="profile" class="ckeditor"></textarea> <span></span></td>
</tr>
<tr>
<th>�ܲ����ڵأ�</th>
<td><input type="text" name="location" id="location" size="20" maxlength="20" /></td>
</tr>
<tr>
<th>��ַ��</th>
<td><input type="text" name="address" id="address" size="100" maxlength="200" /></td>
</tr>
<tr>
<th>�绰��</th>
<td><input type="text" name="tel" id="tel" size="20" maxlength="50" /></td>
</tr>
<tr>
<th>���棺</th>
<td><input type="text" name="fax" id="fax" size="20" maxlength="50" /></td>
</tr>
<tr>
<th>���ʣ�</th>
<td><input type="text" name="email" id="email" size="20" maxlength="50" /></td>
</tr>
<tr>
<th>QQ��</th>
<td><input type="text" name="qq" id="qq" size="20" maxlength="50" /></td>
</tr>
<tr>
<th>��վ��</th>
<td><input type="text" name="website" id="website" size="120" maxlength="255" /></td>
</tr>
<tr>
<th>���꣺</th>
<td><input type="text" name="webshop" id="webshop" size="120" maxlength="255" /></td>
</tr>
<tr>
<th>΢����</th>
<td><input type="text" name="weibo" id="weibo" size="120" maxlength="255" /></td>
</tr>
<tr>
<th>΢�ţ�</th>
<td><input type="text" name="weixin" id="weixin" size="120" maxlength="255" /></td>
</tr>
<tr>
<th>LOGO��</th>
<td>
<input type="text" name="logo" id="logo" size="50" maxlength="100" />
<input class="img_browse" id="logo_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>��ͼ��</th>
<td>
<input type="text" name="pic" id="pic" size="50" maxlength="100" />
<input class="img_browse" id="pic_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>���˴���</th>
<td>
<select name="is_allow_join" id="is_allow_join">
<?php
$arrayJOIN['0'] = '���¼��˴���';
$arrayJOIN['-1'] = '������';
$arrayJOIN['1'] = '����';
arrayToOptions($arrayJOIN,0,1);
?>
</select>
<select name="is_allow_agent" id="is_allow_agent">
<?php
$arrayAGENT['0'] = '���ϴ���';
$arrayAGENT['-1'] = '������';
$arrayAGENT['1'] = '����';
arrayToOptions($arrayAGENT,0,1);
?>
</select>
</td>
</tr>
<tr>
<th>��ע�˴Σ�</th>
<td>
<input type="text" name="hits" id="hits" size="7"  />
<span></span>
</td>
</tr>
<tr>
<th>�ȶ�ָ����</th>
<td>
<input type="text" name="hots" id="hots" size="7"  />
<span></span>
</td>
</tr>
<tr>
<th>��Ӫ������</th>
<td>
<input type="text" name="days" id="days" size="7"  />
<span></span>
</td>
</tr>
<tr>
<th>����Ȩֵ��</th>
<td>
<input type="text" name="order_id" id="order_id" size="5" value="0" />
<span>���������κ�������ȨֵԽ������Խ��ǰ��</span>
</td>
</tr>

<tr>
<th>����״̬��</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" /><label for="is_show">�ݸ�</label></td>
</tr>
<tr>
<th>��ע��</th>
<td><textarea name="remark" id="remark"></textarea></td>
</tr>
<tr><td colspan="2" class="td_blank"></td></tr>
</table>

<div class="fixed_buttonbox">
<input type="submit" name="ok" id="ok_back" value="���沢����" accesskey="s" />
<input type="button" name="cancel" id="cancel" value="ȡ��(��)" onClick="if (confirm('ȷ��Ҫ������')){history.back(1)}" />
</div>

</form>


<?php
require_once("includes/footer.php");
?>
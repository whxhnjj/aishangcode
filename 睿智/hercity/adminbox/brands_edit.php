<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
$id = _GET('id','i');
$sql = "select * from ".$config['tablePre']."brands where id=".$id;
$result = $mysqli->query($sql) or die("��ѯʧ��");
$brand = $result->fetch_array() or die("fail");
$result->free();
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

<form name="form1" id="form1" action="brands_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>��ǩ��</th>
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
<th>Ʒ�����ƣ�</th>
<td><input type="text" name="firstname" id="firstname" size="20" maxlength="20" value="<?=$brand['firstname']?>" /> <span>Ʒ�ƴʡ��硰������</span></td>
</tr>
<tr>
<th>��ҵ���ƣ�</th>
<td><input type="text" name="lastname" id="lastname" size="20" maxlength="20" value="<?=$brand['lastname']?>" /> <span>��ҵ�ʡ�����Ʒ�ƴ�����Ϊһ�����ơ��硰�ʼǱ���</span></td>
</tr>
<tr>
<th>Ӣ�����ƣ�</th>
<td><input type="text" name="ename" id="ename" size="30" maxlength="50" value="<?=$brand['ename']?>" /> <span>Ʒ�Ƶ�Ӣ�����ơ��硰Oncemeet��</span></td>
</tr>
<tr>
<th>������</th>
<td><input type="text" name="domain" id="domain" size="20" maxlength="20" value="<?=$brand['domain']?>" /> <span>Ʒ��Ӣ������ƴ����ɣ���Сд��ĸ�������ո�������ַ����硰oncemeet��</span></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="100" value="<?=$brand['title']?>" /> <span>Ʒ��ҳ�����</span></td>
</tr>
<tr>
<th>�Ƿ���֤��</th>
<td>
<select name="is_auth" id="is_auth">
<?php
$arrayAuth[0] = 'δ��֤';
$arrayAuth[1] = '����֤';
arrayToOptions($arrayAuth,0,1,$brand['is_auth']);
?>
</select>
</td>
</tr>
<tr>
<th>��֤���֣�</th>
<td><input type="text" name="auth_info" id="auth_info" size="100" maxlength="200" value="<?=$brand['auth_info']?>" /> <span></span></td>
</tr>
<tr>
<th>��飺</th>
<td><textarea name="profile" id="profile" class="ckeditor"><?=$brand['profile']?></textarea> <span></span></td>
</tr>
<tr>
<th>�ܲ����ڵأ�</th>
<td><input type="text" name="location" id="location" size="20" maxlength="20" value="<?=$brand['location']?>" /></td>
</tr>
<tr>
<th>��ַ��</th>
<td><input type="text" name="address" id="address" size="100" maxlength="200" value="<?=$brand['address']?>" /></td>
</tr>
<tr>
<th>�绰��</th>
<td><input type="text" name="tel" id="tel" size="20" maxlength="50" value="<?=$brand['tel']?>" /></td>
</tr>
<tr>
<th>���棺</th>
<td><input type="text" name="fax" id="fax" size="20" maxlength="50" value="<?=$brand['fax']?>" /></td>
</tr>
<tr>
<th>���ʣ�</th>
<td><input type="text" name="email" id="email" size="20" maxlength="50" value="<?=$brand['email']?>" /></td>
</tr>
<tr>
<th>QQ��</th>
<td><input type="text" name="qq" id="qq" size="20" maxlength="50" value="<?=$brand['qq']?>" /></td>
</tr>
<tr>
<th>��վ��</th>
<td><input type="text" name="website" id="website" size="120" maxlength="255" value="<?=$brand['website']?>" /></td>
</tr>
<tr>
<th>���꣺</th>
<td><input type="text" name="webshop" id="webshop" size="120" maxlength="255" value="<?=$brand['webshop']?>" /></td>
</tr>
<tr>
<th>΢����</th>
<td><input type="text" name="weibo" id="weibo" size="120" maxlength="255" value="<?=$brand['weibo']?>" /></td>
</tr>
<tr>
<th>΢�ţ�</th>
<td><input type="text" name="weixin" id="weixin" size="120" maxlength="255" value="<?=$brand['weixin']?>" /></td>
</tr>
<tr>
<th>LOGO��</th>
<td>
<input type="text" name="logo" id="logo" size="50" maxlength="100" value="<?=$brand['logo']?>" />
<input class="img_browse" id="logo_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>��ͼ��</th>
<td>
<input type="text" name="pic" id="pic" size="50" maxlength="100" value="<?=$brand['pic']?>" />
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
arrayToOptions($arrayJOIN,0,1,$brand['is_allow_join']);
?>
</select>

<select name="is_allow_agent" id="is_allow_agent">
<?php
$arrayAGENT['0'] = '���ϴ���';
$arrayAGENT['-1'] = '������';
$arrayAGENT['1'] = '����';
arrayToOptions($arrayAGENT,0,1,$brand['is_allow_agent']);
?>
</select>
</td>
</tr>
<tr>
<th>��ע�˴Σ�</th>
<td>
<input type="text" name="hits" id="hits" size="7" value="<?=$brand['hits']?>" />
<span></span>
</td>
</tr>
<tr>
<th>�ȶ�ָ����</th>
<td>
<input type="text" name="hots" id="hots" size="7" value="<?=$brand['hots']?>" />
<span></span>
</td>
</tr>
<tr>
<th>��Ӫ������</th>
<td>
<input type="text" name="days" id="days" size="7" value="<?=$brand['days']?>" />
<span></span>
</td>
</tr>
<tr>
<th>����Ȩֵ��</th>
<td>
<input type="text" name="order_id" id="order_id" size="5" value="<?=$brand['order_id']?>" />
<span>���������κ�������ȨֵԽ������Խ��ǰ��</span>
</td>
</tr>
<tr>
<th>����״̬��</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" <?php if(!$brand['is_show']) echo('checked="checked"');?> /><label for="is_show">�ݸ�</label></td>
</tr>
<tr>
<th>��ע��</th>
<td><textarea name="remark" id="remark"><?=$brand['remark']?></textarea></td>
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
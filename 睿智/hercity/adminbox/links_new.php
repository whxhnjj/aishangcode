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

//����֤
$("#form1").submit( function () {

	if ($("#title").val() == '')
	{
	alert("���ⲻ��Ϊ�գ�");
	$("#title").focus();
	return false;
	}
	
	if ($("#linkto").val() == '')
	{
	alert("���ӵ�ַ����Ϊ�գ�");
	$("#linkto").focus();
	return false;
	}
	
  return true;
}); 

});

//-->
</script>

<div id="path">
loftCMS -> ���ӹ��� -> �½�
</div>

<form name="form1" id="form1" action="links_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>���ͣ�</th>
<td>
<select name="cat" id="cat">
<?php
arrayToOptions($config['linkCats'],0,1,$cat);
?>
</select>
</td>
</tr>
<tr>
<th>����Ȩֵ��</th>
<td><input type="text" name="order_id" id="order_id" size="5" maxlength="5" value="0" /></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>���ӵ���</th>
<td>
<textarea name="linkto" id="linkto"></textarea>
</td>
</tr>
<tr>
<th>����ͼ��</th>
<td>
<input type="text" name="thumb1" id="thumb1" size="50" maxlength="100" />
<input class="img_browse" id="thumb1_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
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
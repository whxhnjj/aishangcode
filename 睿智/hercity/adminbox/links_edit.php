<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _GET('id','i');
$sql = "select cat,title,linkto,thumb1,order_id from ".$config['tablePre']."links where id=".$id;
$result = $mysqli->query($sql) or die("��ѯʧ��");
list($cat,$title,$linkto,$thumb1,$order_id)=$result->fetch_row() or die("fail");
$result->free();
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
loftCMS -> ���ӹ��� -> �޸�
</div>

<form name="form1" id="form1" action="links_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
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
<td><input type="text" name="order_id" id="order_id" size="5" maxlength="5" value="<?=$order_id?>" /></td>
</tr>
<tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" value="<?=$title?>" /></td>
</tr>
<tr>
<th>���ӵ���</th>
<td>
<textarea name="linkto" id="linkto" ><?=$linkto?></textarea>
</td>
</tr>
<tr>
<th>����ͼ��</th>
<td>
<input type="text" name="thumb1" id="thumb1" size="50" maxlength="100" value="<?=$thumb1?>" />
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
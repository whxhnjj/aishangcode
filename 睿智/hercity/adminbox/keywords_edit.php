<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _GET('id','i');
$sql = "select name,ename,title,description,linkto,hits from ".$config['tablePre']."keywords where id=".$id;
$result = $mysqli->query($sql) or die("��ѯʧ��");
list($name,$ename,$title,$description,$linkto,$hits) = $result->fetch_row() or die("fail");
$result->free();
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
loftCMS -> �ؼ��ʹ��� -> �޸�
</div>

<form name="form1" id="form1" action="keywords_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="old_name" id="old_name" value="<?=$name?>" />
<input type="hidden" name="hits" id="hits" value="<?=$hits?>" />

<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<tr>
<th>��ǩ��</th>
<td><input type="text" name="name" id="name" size="50" maxlength="50" value="<?=$name?>" /></td>
</tr>
<tr>
<th>Ӣ������</th>
<td><input type="text" name="ename" id="ename" size="50" maxlength="50" value="<?=$ename?>" /></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="80" maxlength="100" value="<?=$title?>" /></td>
</tr>
<tr>
<th>������</th>
<td><textarea name="description" id="description" /><?=$description?></textarea></td>
</tr>
<tr>
<th>���ӵ���</th>
<td><textarea name="linkto" id="linkto" /><?=$linkto?></textarea></td>
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
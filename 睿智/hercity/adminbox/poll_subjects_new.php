<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>

<div id="path">
loftCMS -> ͶƱ���� -> �½�
</div>

<form name="form1" id="form1" action="poll_subjects_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>�������£�</th>
<td><input type="text" name="post_id" id="post_id" size="10" maxlength="10" value="0" /> ��������ID�������������뱣��Ĭ��ֵ0��</td>
</tr>
<tr>
<th>ͶƱ���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>����˵����</th>
<td>
<textarea name="content" id="content" style="width:98%;height:200px"></textarea>
</td>
</tr>
<tr>
<th>����ʱ�䣺</th>
<td>
<input type="text" name="y" id="y" size="4" maxlength="4" />-
<input type="text" name="m" id="m" size="2" maxlength="2" />-
<input type="text" name="d" id="d" size="2" maxlength="2" />&nbsp;&nbsp;
<input type="text" name="h" id="h" size="2" maxlength="2" />:
<input type="text" name="i" id="i" size="2" maxlength="2" />:
<input type="text" name="s" id="s" size="2" maxlength="2" />
���޽���ʱ����ȫ�����ա�
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
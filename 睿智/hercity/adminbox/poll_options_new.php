<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
$subject_id = _GET('subject_id','i');
?>

<div id="path">
loftCMS -> ͶƱ���� -> ��ѡ����� -> �½�
</div>

<form name="form1" id="form1" action="poll_options_new_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="subject_id" id="subject_id" value="<?=$subject_id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>��ţ�</th>
<td><input type="text" name="number" id="number" size="3" maxlength="4" value="0" /></td>
</tr>
<tr>
<th>���⣺</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" /></td>
</tr>
<tr>
<th>Ʊ����</th>
<td><input type="text" name="votes" id="votes" size="10" maxlength="10" value="0" /></td>
</tr>
<tr>
<th>���ӵ���</th>
<td><input type="text" name="linkto" id="linkto" size="85" maxlength="200" /></td>
</tr>
<tr>
<th>���ݣ�</th>
<td>
<textarea name="content" id="content" style="width:98%;height:200px"></textarea>
</td>
</tr>
<tr>
<th>��ע��</th>
<td><textarea name="info" id="info" style="width:98%;height:100px"></textarea></td>
</tr>
<tr>
<th>����ͼ1��</th>
<td>
<input type="text" name="thumb1" id="thumb1" size="50" maxlength="100" />
<input class="img_browse" id="thumb1_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>����ͼ2��</th>
<td>
<input type="text" name="thumb2" id="thumb2" size="50" maxlength="100" />
<input class="img_browse" id="thumb2_browse" type="file" name="fileToUpload" />
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
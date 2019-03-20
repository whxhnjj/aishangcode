<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
require_once("includes/post_inc.php");

$kind_id = _GET('kind_id','i'); //��ĿID
$special = _GET('special','i',0); //����ģ��ID
$url = _GET('u');
$kind_info = kindsGetInfo($kind_id);
$template = ($kind_info['showtemplate'] == '' ) ? "show.html" : $kind_info['showtemplate'];

$y = date("Y");
$m = date("n");
$d = date("j");
$h = date("G");
$i = date("i");
$s = date("s");
?>

<div id="path">
loftCMS -> ���¹��� -> �½�(<?=$config['specialModel'][$special]?>)
</div>

<form name="form1" id="form1" action="posts_new_action.php" method="post" enctype="multipart/form-data" >
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<input type="hidden" name="special" id="special" value="<?=$special?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>���⣺</th>
<td>
<input type="text" name="title" id="title" size="50" maxlength="50" style="width:<?=$config['postTitleLen']*12?>px" /> 
<span>������<?=$config['postTitleLen']?>�����֡�</span>
</td>
</tr>
<tr>
<th>�̱��⣺</th>
<td>
<input type="text" name="subhead1" id="subhead1" size="50" maxlength="50" style="width:<?=$config['postSubhead1Len']*12?>px" /> <span>������<?=$config['postSubhead1Len']?>�����֡�</span>
</td>
</tr>
<tr>
<th>�����⣺</th>
<td>
<input type="text" name="subhead2" id="subhead2" size="50" maxlength="50" style="width:<?=$config['postSubhead2Len']*12?>px" /> <span>������<?=$config['postSubhead2Len']?>�����֡�</span>
</td>
</tr>
<tr>
<th>�ؼ��ʣ�</th>
<td>
<input type="text" name="keyword" id="keyword" size="80" maxlength="200" />
<input type="button" id="get_keywords" name="get_keywords" value="�Զ���ȡ" />
<span id="keyword_msg">����ؼ������ö��Ÿ���</span>
</td>
</tr>
<tr>
<th>ժҪ��</th>
<td>
<textarea name="brief" id="brief" ></textarea>
<div id="brief_msg">����������<span><?=$config['postBriefMaxLen']?></span>���ַ�</div>
</td>
</tr>
<tr>
<th>����ʱ�䣺</th>
<td>
<input type="text" name="y" id="y" size="4" maxlength="4" value="<?=$y?>" />-
<input type="text" name="m" id="m" size="2" maxlength="2" value="<?=$m?>" />-
<input type="text" name="d" id="d" size="2" maxlength="2" value="<?=$d?>" />&nbsp;&nbsp;
<input type="text" name="h" id="h" size="2" maxlength="2" value="<?=$h?>" />:
<input type="text" name="i" id="i" size="2" maxlength="2" value="<?=$i?>" />:
<input type="text" name="s" id="s" size="2" maxlength="2" value="<?=$s?>" />
<span>Ĭ��Ϊ��ǰʱ�䡣</span>
</td>
</tr>
<tr>
<th>���ӵ���</th>
<td><textarea name="linkto" id="linkto" ></textarea><span></span></td>
</tr>


<?php
//����ģ��ר��
if($special >= 0)
{
?>

<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>���ݣ�</th>
<td>
<textarea name="content" id="content" class="ckeditor"></textarea><span></span>
</td>
</tr>


<tr>
<th>��ͼ��</th>
<td>


<div id="queue"></div>
<input id="file_upload" name="file_upload" type="file" multiple="true">
<!--
<a class="uploadfive_upload" href="javascript:$('#file_upload').uploadifive('upload')">�ϴ�</a>
<a class="uploadfive_clear" href="javascript:$('#file_upload').uploadifive('clearQueue')">ȡ�������ϴ�</a>
-->
</td>
</tr>


<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>���ߣ�</th>
<td>
<input type="text" name="author" id="author" size="30" maxlength="50" />
<select class="selector" name="author_select" id="author_select" size="1">
<option value="" class="inactive">ѡ��</option>
<?php
arrayToOptions($config['postAuthors'],1,1);
?>
</select>
</td>
</tr>
<tr>
<th>��Դ��</th>
<td>
<input type="text" name="source" id="source" size="50" maxlength="50" />
<select class="selector" name="source_select" id="source_select" size="1">
<option value="" class="inactive">ѡ��</option>
<?php
arrayToOptions($config['postSources'],1,1);
?>
</select>
</td>
</tr>
<tr>
<th>ý���ļ���</th>
<td><input type="text" name="mediafile" id="mediafile" size="85" maxlength="100" /> ֱ������ý���ļ�·��</td>
</tr>
<tr>
<th>ģ�壺</th>
<td>
<select name="template" id="template" size="1">
<option value="" class="inactive">ѡ��ģ��</option>
<?php
arrayToOptions($config['showTemplates'],1,1,$template,1);
?>
</select>
<span></span>
</td>
</tr>
<?php
}
?>

<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
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
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>������Ŀ��</th>
<td>
<select name="kind_id" id="kind_id" size="1">
<option value="0" class="inactive">ѡ����Ŀ</option>
<?php
kindsSelectList(0,$kind_id,0,0);
?>
</select>
<span></span>
</td>
</tr>
<tr>
<th>����Ȩֵ��</th>
<td>
<input type="text" name="order_id" id="order_id" value="0" size="5" />
<span>���������κ�������ȨֵԽ������Խ��ǰ��</span>
</td>
</tr>
<tr>
<th>�Ƽ�λ�ã�</th>
<td>
<?php
arrayToCheckbox('arrposid',$config['arrposid']);
?>
</td>
</tr>
<tr>
<th>����״̬��</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" <?php echo($config['postDefaultStatus']?"":"checked=\"checked\"");?> /><label for="is_show">�ݸ�</label></td>
</tr>
<tr><td colspan="2" class="td_blank"></td></tr>
</table>

<div class="fixed_buttonbox">
<input type="submit" name="ok" id="ok_back" value="���沢����" accesskey="s" />
<input type="submit" name="ok" id="ok_continue" value="���沢����" accesskey="c" />
<input type="button" name="cancel" id="cancel" value="ȡ��(��)" onClick="if (confirm('ȷ��Ҫ������')){location.href='<?=$url?>'}" />
</div>

</form>


<?php
require_once("includes/footer.php");
?>
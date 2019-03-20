<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
require_once("includes/post_inc.php");

$id = _GET('id','i');
$page_no = _GET('page','i',1);
$url = _GET('u');

$sql = "select kind_id,template,title,subhead1,subhead2,keyword,author,source,linkto,dateline,brief,thumb1,thumb2,mediafile,is_show,arrposid,order_id,special from ".$config['tablePre']."posts where id=".$id;
$result = $mysqli->query($sql) or die("查询失败");
list($kind_id,$template,$title,$subhead1,$subhead2,$keyword,$author,$source,$linkto,$dateline,$brief,$thumb1,$thumb2,$mediafile,$is_show,$arrposid,$order_id,$special)=$result->fetch_row() or die("fail");
$result->free();


$sql = "select content from ".$config['tablePre']."postcontents where post_id=".$id." and page_no=".$page_no;
$result = $mysqli->query($sql) or die("查询失败");
list($content)=$result->fetch_row() or $content='';
$result->free();

$y = date("Y",$dateline);
$m = date("n",$dateline);
$d = date("j",$dateline);
$h = date("G",$dateline);
$i = date("i",$dateline);
$s = date("s",$dateline);
?>

<div id="path">
loftCMS -> 文章管理 -> 修改
</div>

<form name="form1" id="form1" action="posts_edit_action.php" method="post" enctype="multipart/form-data" >
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="keyword_original" id="keyword_original" value="<?=$keyword?>" />
<input type="hidden" name="page_no" id="page_no" value="<?=$page_no?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>标题：</th>
<td>
<input type="text" name="title" id="title" size="50" maxlength="50" value="<?=$title?>" style="width:<?=$config['postTitleLen']*12?>px" />
<span>不超过<?=$config['postTitleLen']?>个汉字。</span>
</tr>
<tr>
<th>短标题：</th>
<td>
<input type="text" name="subhead1" id="subhead1" size="50" maxlength="50" value="<?=$subhead1?>" style="width:<?=$config['postSubhead1Len']*12?>px" />
<span>不超过<?=$config['postSubhead1Len']?>个汉字。</span>
</td>
</tr>
<tr>
<th>长标题：</th>
<td>
<input type="text" name="subhead2" id="subhead2" size="50" maxlength="50" value="<?=$subhead2?>" style="width:<?=$config['postSubhead2Len']*12?>px" />
<span>不超过<?=$config['postSubhead2Len']?>个汉字。</span>
</td>
</tr>
<tr>
<th>关键词：</th>
<td>
<input type="text" name="keyword" id="keyword" size="80" maxlength="200" value="<?=$keyword?>" /> <input type="button" id="get_keywords" name="get_keywords" value="自动提取" />
<span id="keyword_msg">多个关键词请用逗号隔开</span>
</td>
</tr>
<tr>
<th>摘要：</th>
<td>
<textarea name="brief" id="brief" ><?=$brief?></textarea>
<div id="brief_msg">还可以输入<span><?=$config['postBriefMaxLen']?></span>个字符</div>
</td>
</tr>
<tr>
<th>发布时间：</th>
<td>
<input type="text" name="y" id="y" size="4" maxlength="4" value="<?=$y?>" />-
<input type="text" name="m" id="m" size="2" maxlength="2" value="<?=$m?>" />-
<input type="text" name="d" id="d" size="2" maxlength="2" value="<?=$d?>" />&nbsp;&nbsp;
<input type="text" name="h" id="h" size="2" maxlength="2" value="<?=$h?>" />:
<input type="text" name="i" id="i" size="2" maxlength="2" value="<?=$i?>" />:
<input type="text" name="s" id="s" size="2" maxlength="2" value="<?=$s?>" />
<span>默认为当前时间。</span>
</td>
</tr>
<tr>
<th>链接到：</th>
<td><textarea name="linkto" id="linkto" ><?=$linkto?></textarea><span></span></td>
</tr>

<?php
//文章模型专用
if($special >= 0)
{
?>
<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>内容：</th>
<td>
<textarea name="content" id="content" class="ckeditor" ><?=$content?></textarea>
</td>
</tr>
<tr>
<th>插图：</th>
<td>
<div id="queue"></div>
<input id="file_upload" name="file_upload" type="file" multiple="true">
<!--
<a class="uploadfive_upload" href="javascript:$('#file_upload').uploadifive('upload')">上传</a>
<a class="uploadfive_clear" href="javascript:$('#file_upload').uploadifive('clearQueue')">取消所有上传</a>
-->
</td>
</tr>

<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>作者：</th>
<td>
<input type="text" name="author" id="author" size="30" maxlength="50" value="<?=$author?>" />
<select class="selector" name="author_selector" id="author_selector" size="1">
<option value="" class="inactive">选择</option>
<?php
arrayToOptions($config['postAuthors'],1,1,$author,1);
?>
</select>
</td>
</tr>
<tr>
<th>来源：</th>
<td>
<input type="text" name="source" id="source" size="50" maxlength="50" value="<?=$source?>" />
<select class="selector" name="source_selector" id="source_selector" size="1">
<option value="" class="inactive">选择</option>
<?php
arrayToOptions($config['postSources'],1,1,$source,1);
?>
</select>
</td>
</tr>
<tr>
<th>媒体文件：</th>
<td>
<input type="text" name="mediafile" id="mediafile" size="85" maxlength="100" value="<?=$mediafile?>" />
<span>直接输入媒体文件路径</span>
</td>
</tr>
<tr>
<th>模板：</th>
<td>
<select name="template" id="template" size="1">
<option value="" class="inactive">选择模板</option>
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
<th>缩略图1：</th>
<td>
<input type="text" name="thumb1" id="thumb1" size="50" maxlength="100" value="<?=$thumb1?>" />
<input class="img_browse" id="thumb1_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>
<tr>
<th>缩略图2：</th>
<td>
<input type="text" name="thumb2" id="thumb2" size="50" maxlength="100" value="<?=$thumb2?>" />
<input class="img_browse" id="thumb2_browse" type="file" name="fileToUpload" />
<img class="loading" src="libraries/ajaxfileupload/loading.gif" />
</td>
</tr>

<tr>
<td colspan="2" class="tableLine"><a href="#">TOP</a></td>
</tr>

<tr>
<th>所属栏目：</th>
<td>
<select name="kind_id" id="kind_id" size="1">
<option value="0" class="inactive">选择栏目</option>
<?php
kindsSelectList(0,$kind_id,0,0);
?>
</select>
<span></span>
</td>
</tr>
<tr>
<th>排序权值：</th>
<td>
<input type="text" name="order_id" id="order_id" size="5" value="<?=$order_id?>" />
<span>可以输入任何整数。权值越大，排序越靠前。</span>
</td>
</tr>
<tr>
<th>推荐位置：</th>
<td>
<?php
arrayToCheckbox('arrposid',$config['arrposid'],0,1,$arrposid);
?>
</td>
</tr>
<tr>
<th>保存状态：</th>
<td><input type="checkbox" name="is_show" id="is_show" value="0" <?php if(!$is_show) echo('checked="checked"');?> /><label for="is_show">草稿</label></td>
</tr>
<tr>
<th>特殊模型：</th>
<td>
<select name="special" id="special" size="1">
<?php
arrayToOptions($config['specialModel'],0,1,$special);
?>
</select>
</td>
</tr>
<tr><td colspan="2" class="td_blank"></td></tr>
</table>

<div class="fixed_buttonbox">
<input type="submit" name="ok" id="ok_back" value="保存并返回" accesskey="s" />
<input type="submit" name="ok" id="ok_continue" value="保存并继续" accesskey="c" />
<input type="button" name="cancel" id="cancel" value="取消(×)" onClick="if (confirm('确定要放弃吗？')){location.href='<?=$url?>'}" />
</div>

</form>

<?php
require_once("includes/footer.php");
?>
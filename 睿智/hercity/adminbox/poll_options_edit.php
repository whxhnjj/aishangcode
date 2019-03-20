<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _GET('id','i');
$sql = "select number,title,votes,linkto,content,info,thumb1,thumb2 from ".$config['tablePre']."poll_options where id=".$id;
$result = $mysqli->query($sql) or die("查询失败");
list($number,$title,$votes,$linkto,$content,$info,$thumb1,$thumb2)=$result->fetch_row() or die("fail");
$result->free();
?>

<div id="path">
loftCMS -> 投票管理 -> 备选项管理 -> 修改
</div>

<form name="form1" id="form1" action="poll_options_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>编号：</th>
<td><input type="text" name="number" id="number" size="3" maxlength="4" value="<?=$number?>" /></td>
</tr>
<tr>
<th>标题：</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" value="<?=$title?>" /></td>
</tr>
<tr>
<th>票数：</th>
<td><input type="text" name="votes" id="votes" size="10" maxlength="10" value="<?=$votes?>" /></td>
</tr>
<tr>
<th>链接到：</th>
<td><input type="text" name="linkto" id="linkto" size="85" maxlength="200" value="<?=$linkto?>" /></td>
</tr>
<tr>
<th>内容：</th>
<td>
<textarea name="content" id="content" style="width:98%;height:200px"><?=$content?></textarea>
</td>
</tr>
<tr>
<th>备注：</th>
<td><textarea name="info" id="info" style="width:98%;height:100px"><?=$info?></textarea></td>
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
<td colspan="2" class="buttonbox">
<input type="submit" name="ok" id="ok_back" value="确定" accesskey="s" />
<input type="button" name="cancel" id="cancel" value="取消" onClick="history.back(1)" />
</td>
</tr>
</table>

</form>


<?php
require_once("includes/footer.php");
?>
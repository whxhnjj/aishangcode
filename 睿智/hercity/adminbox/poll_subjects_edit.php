<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _GET('id','i');

$sql = "select post_id,title,content,expiration from ".$config['tablePre']."poll_subjects where id=".$id;
$result = $mysqli->query($sql) or die("查询失败");
list($post_id,$title,$content,$expiration)=$result->fetch_row() or die("fail");
$result->free();

$y = $expiration?date("Y",$expiration):"";
$m = $expiration?date("m",$expiration):"";
$d = $expiration?date("d",$expiration):"";
$h = $expiration?date("H",$expiration):"";
$i = $expiration?date("i",$expiration):"";
$s = $expiration?date("s",$expiration):"";
?>

<div id="path">
loftCMS -> 投票管理 -> 修改
</div>

<form name="form1" id="form1" action="poll_subjects_edit_action.php" method="post" >
<input type="hidden" name="url" id="url" value="<?=$_SERVER["HTTP_REFERER"]?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>关联文章：</th>
<td>
<input type="text" name="post_id" id="post_id" size="10" maxlength="10" value="0" />
输入文章ID，不关联文章请保留默认值0。
</td>
</tr>
<tr>
<th>投票主题：</th>
<td><input type="text" name="title" id="title" size="50" maxlength="50" value="<?=$title?>" /></td>
</tr>
<tr>
<th>主题说明：</th>
<td>
<textarea name="content" id="content" style="width:98%;height:200px"><?=$content?></textarea>
</td>
</tr>
<tr>
<th>结束时间：</th>
<td>
<input type="text" name="y" id="y" size="4" maxlength="4" value="<?=$y?>" />-
<input type="text" name="m" id="m" size="2" maxlength="2" value="<?=$m?>" />-
<input type="text" name="d" id="d" size="2" maxlength="2" value="<?=$d?>" />&nbsp;&nbsp;
<input type="text" name="h" id="h" size="2" maxlength="2" value="<?=$h?>" />:
<input type="text" name="i" id="i" size="2" maxlength="2" value="<?=$i?>" />:
<input type="text" name="s" id="s" size="2" maxlength="2" value="<?=$s?>" />
不限结束时间请全部留空。
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
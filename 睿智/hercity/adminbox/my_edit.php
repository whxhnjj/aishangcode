<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

session_start();
$id = $_SESSION['userid'];

$sql = "select uname,nickname,email from ".$config['tablePre']."users where id=".$id;
$result = $mysqli->query($sql) or die("fail");
list($uname,$nickname,$email)=$result->fetch_row() or die("fail");
$result->free();
?>

<div id="path">
loftCMS -> �������� -> �޸ĸ�������
</div>

<form name="form1" id="form1" action="my_edit_action.php" method="post" >
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<table cellpadding="6" cellspacing="0" class="formTable">
<tr>
<th>�û�����</th>
<td><?=$uname?></td>
</tr>
<tr>
<th>���룺</th>
<td><input type="text" name="password" id="password" size="50" maxlength="50" value="" /> <span>���ձ�ʾ���޸�</span></td>
</tr>
<tr>
<th>�ǳƣ�</th>
<td><input type="text" name="nickname" id="nickname" size="50" maxlength="50" value="<?=$nickname?>" /></td>
</tr>
<tr>
<th>E-mail��</th>
<td><input type="text" name="email" id="email" size="50" maxlength="80" value="<?=$email?>" /></td>
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
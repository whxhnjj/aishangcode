<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>

<div id="path">
loftCMS -> ������� -> ����
</div>

<form name="form1" action="persons_index.php" method="get" class="form_search">

<label for="search_field">�����ֶΣ�</label>
<select name="search_field" id="search_field">
<option value="id">ID</option>
<option value="name" selected="selected">����</option>
<option value="ename">Ӣ����</option>
</select>
<label for="search_key">�ؼ��ʣ�</label>
<input type="text" name="search_key" id="search_key" />
<input type="submit" name="submit" value="����" />
</form>

<?php
require_once("includes/footer.php");
?>
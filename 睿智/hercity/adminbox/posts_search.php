<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>

<div id="path">
loftCMS -> 文章管理 -> 搜索
</div>

<form name="form1" action="posts_index.php" method="get" class="form_search">

<label for="search_field">搜索字段：</label>
<select name="search_field" id="search_field">
<option value="id">ID</option>
<option value="title">标题</option>
<option value="keyword">关键词</option>
<option value="brief">摘要</option>
<option value="content">内容</option>
</select>

<label for="search_key">关键词：</label>
<input type="text" name="search_key" id="search_key" />
<input type="submit" name="submit" value="搜索" />
</form>


<?php
require_once("includes/footer.php");
?>
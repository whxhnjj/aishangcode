<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=gb2312">
<title>loftcms</title>
<script type="text/javascript" src="assets/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="libraries/dynatree/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="libraries/dynatree/jquery.cookie.js"></script>
<script type="text/javascript" src="libraries/dynatree/jquery.dynatree.min.js"></script>
<link href="libraries/dynatree/skin/ui.dynatree.css" rel="stylesheet" type="text/css" />
<link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
	$("#tree").dynatree({
//		persist: true,
		onClick: function(dtnode) {
			if( dtnode.data.url )
			{
				$("#main",parent.document.body).attr("src",dtnode.data.url);
			}
			}
	});
});
</script>
</head>

<body id="menu">

<h1>菜单 MENU</h1>

<div id="tree">
<ul style="display:none">

<?php
if ($_SESSION['usergroup'] >= $config['rights']['posts']){
?>
<li id="li_1" title="文章管理" class="folder">文章管理
<?php
kindsTreeList(0,1,"posts_index.php?kind_id=",0)
?>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['comments']){
?>
<li id="li_2" class="folder">评论管理
	<ul>
	<li id="li_2_1" data="url:'comments_index.php',target:'main'">评论管理</li>
	<li id="li_2_2" data="url:'comments_search.php',target:'main'">评论搜索</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['attachments']){
?>
<li id="li_3" class="folder">附件管理
	<ul>
	<li id="li_3_1" data="url:'attachments_index.php',target:'main'">附件管理</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['crons']){
?>
<li id="li_4" class="folder">任务管理
	<ul>
	<li id="li_4_1" data="url:'crons_write_index.php',target:'main'">重写HTML页面</li>
	<li id="li_4_2" data="url:'crons_clear_index.php',target:'main'">清除系统垃圾</li>
	<li id="li_4_3" data="url:'crons_update_index.php',target:'main'">更新数据统计</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['poll']){
?>
<li id="li_5" class="folder">投票管理
	<ul>
	<li id="li_5_1" data="url:'poll_subjects_index.php',target:'main'">投票管理</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['persons']){
?>
<li id="li_12" class="folder">人物管理
	<ul>
	<?php
	$i = 0;
	while ($cat = each($config['personCats']))
	{
	$i++;
	printf("<li id=\"li_12_%d\" data=\"url:'persons_index.php?cat=%d',target:'main'\">%s</li>",$i,$cat[0],$cat[1]);
	}
	?>
	</ul>
</li>
<?php
}if ($_SESSION['usergroup'] >= $config['rights']['brands']){
?>
<li id="li_13" class="folder">品牌管理
	<ul>
	<li id="li_13_1" data="url:'brands_index.php',target:'main'">全部品牌</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['links']){
?>
<li id="li_10" class="folder">链接管理
	<ul>
	<?php
	$i = 0;
	while ($cat = each($config['linkCats']))
	{
	$i++;
	printf("<li id=\"li_10_%d\" data=\"url:'links_index.php?cat=%d',target:'main'\">%s</li>",$i,$cat[0],$cat[1]);
	}
	?>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['keywords']){
?>
<li id="li_11" class="folder">标签管理
	<ul>
	<li id="li_11_1" data="url:'keywords_index.php',target:'main'">关键词管理</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['plugins']){
?>
<li id="li_6" class="folder">插件管理
	<ul>
	<?php
	$plugins = scandir(realpath("plugins/"));
	while($plugin = current($plugins))
	if ($plugin == "." || $plugin == "..")
	{
	next($plugins);
	}
	else
	{
	?>
	<li id="li_6_1" data="url:'plugins/<?=$plugin?>/index.php',target:'main'"><?=$plugin?></li>
	<?php
	next($plugins);
	}
	?>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['kinds']){
?>
<li id="li_7" class="folder">栏目管理
	<ul>
	<li id="li_7_1" data="url:'kinds_index.php',target:'main'">栏目管理</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['users']){
?>
<li id="li_8" class="folder">用户管理
	<ul>
	<li id="li_8_1" data="url:'users_index.php?usergroup=1',target:'main'">投稿者</li>
	<li id="li_8_2" data="url:'users_index.php?usergroup=2',target:'main'">作者</li>
	<li id="li_8_3" data="url:'users_index.php?usergroup=3',target:'main'">编辑</li>

	<?php
	if ($_SESSION['usergroup'] >= 4){
	?>
	<li id="li_8_4" data="url:'users_index.php?usergroup=4',target:'main'">主编</li>
	<?php
	}
	if ($_SESSION['usergroup'] >= 5){
	?>
	<li id="li_8_5" data="url:'users_index.php?usergroup=5',target:'main'">管理员</li>
	<?php
	}
	?>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['system']){
?>
<li id="li_9" class="folder">系统管理
	<ul>
	<li id="li_9_1" data="url:'system_logs_index.php',target:'main'">登录日志</li>
	<li id="li_9_2" data="url:'system_stats.php',target:'main'">数据统计</li>
	<li id="li_9_3" data="url:'system_logstats.php',target:'main'">考勤记录</li>
	</ul>
</li>
<?php
}
?>

</ul>
</div>

<div><?=$config['softVersion']?></div>
	
</body>
</html>
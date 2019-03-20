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

<h1>�˵� MENU</h1>

<div id="tree">
<ul style="display:none">

<?php
if ($_SESSION['usergroup'] >= $config['rights']['posts']){
?>
<li id="li_1" title="���¹���" class="folder">���¹���
<?php
kindsTreeList(0,1,"posts_index.php?kind_id=",0)
?>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['comments']){
?>
<li id="li_2" class="folder">���۹���
	<ul>
	<li id="li_2_1" data="url:'comments_index.php',target:'main'">���۹���</li>
	<li id="li_2_2" data="url:'comments_search.php',target:'main'">��������</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['attachments']){
?>
<li id="li_3" class="folder">��������
	<ul>
	<li id="li_3_1" data="url:'attachments_index.php',target:'main'">��������</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['crons']){
?>
<li id="li_4" class="folder">�������
	<ul>
	<li id="li_4_1" data="url:'crons_write_index.php',target:'main'">��дHTMLҳ��</li>
	<li id="li_4_2" data="url:'crons_clear_index.php',target:'main'">���ϵͳ����</li>
	<li id="li_4_3" data="url:'crons_update_index.php',target:'main'">��������ͳ��</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['poll']){
?>
<li id="li_5" class="folder">ͶƱ����
	<ul>
	<li id="li_5_1" data="url:'poll_subjects_index.php',target:'main'">ͶƱ����</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['persons']){
?>
<li id="li_12" class="folder">�������
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
<li id="li_13" class="folder">Ʒ�ƹ���
	<ul>
	<li id="li_13_1" data="url:'brands_index.php',target:'main'">ȫ��Ʒ��</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['links']){
?>
<li id="li_10" class="folder">���ӹ���
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
<li id="li_11" class="folder">��ǩ����
	<ul>
	<li id="li_11_1" data="url:'keywords_index.php',target:'main'">�ؼ��ʹ���</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['plugins']){
?>
<li id="li_6" class="folder">�������
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
<li id="li_7" class="folder">��Ŀ����
	<ul>
	<li id="li_7_1" data="url:'kinds_index.php',target:'main'">��Ŀ����</li>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['users']){
?>
<li id="li_8" class="folder">�û�����
	<ul>
	<li id="li_8_1" data="url:'users_index.php?usergroup=1',target:'main'">Ͷ����</li>
	<li id="li_8_2" data="url:'users_index.php?usergroup=2',target:'main'">����</li>
	<li id="li_8_3" data="url:'users_index.php?usergroup=3',target:'main'">�༭</li>

	<?php
	if ($_SESSION['usergroup'] >= 4){
	?>
	<li id="li_8_4" data="url:'users_index.php?usergroup=4',target:'main'">����</li>
	<?php
	}
	if ($_SESSION['usergroup'] >= 5){
	?>
	<li id="li_8_5" data="url:'users_index.php?usergroup=5',target:'main'">����Ա</li>
	<?php
	}
	?>
	</ul>
</li>
<?php
}
if ($_SESSION['usergroup'] >= $config['rights']['system']){
?>
<li id="li_9" class="folder">ϵͳ����
	<ul>
	<li id="li_9_1" data="url:'system_logs_index.php',target:'main'">��¼��־</li>
	<li id="li_9_2" data="url:'system_stats.php',target:'main'">����ͳ��</li>
	<li id="li_9_3" data="url:'system_logstats.php',target:'main'">���ڼ�¼</li>
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
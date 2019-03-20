<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


if (_GET('format')=='excel')
{
header("Content-Type: application/vnd.ms-excel; charset=UTF-8"); 
header("Content-Disposition: attachment;filename=system_stats_".date("Ymd").".xls ");
header("Content-Transfer-Encoding: binary ");
}
else

{
//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 系统管理 -> 数据统计");
echo ("<ul id=\"tools\">");
printf ("<li><a href=\"?format=excel\">导出Excel</a></li>");
echo ("</ul>");
echo ("</div>");
}

echo ("<div id=\"formbox\">");
echo ("<form name=\"search\" action=\"\">");

echo ("</form>");
echo ("</div>");








//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="text-align:left">栏目名称</th>');
echo('<th style="width:90px;">文章数</th>');
echo('<th style="width:90px;">点击数</th>');
echo('<th style="width:90px;">评论数</th>');
echo('<th style="width:90px;">文章比</th>');
echo('<th style="width:90px;">点击比</th>');
echo('<th style="width:90px;">评论比</th>');
echo('<th style="width:90px;">点击指数</th>');
echo('<th style="width:90px;">评论指数</th>');
echo('</tr>');


$sql = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where is_show";
$result = $mysqli->query($sql);
list($posts_all,$hits_all,$comments_all) = $result->fetch_row();

//调用listKinds方法递归列出栏目
$rowmum = kindsStat(0,0);


echo('<tr>');
printf('<td>合计</td>');
printf('<td>%d</td>',$posts_all);
printf('<td>%d</td>',$hits_all);
printf('<td>%d</td>',$comments_all);
printf('<td>%s</td>','100%');
printf('<td>%s</td>','100%');
printf('<td>%s</td>','100%');
printf('<td>%s</td>',round($hits_all/$posts_all,2));
printf('<td>%s</td>',round($comments_all/$posts_all,2));
echo('</tr>');


echo('</table>');


$mysqli->close();
require_once("includes/footer.php");





//kindsTableList
//递归列出栏目,用于栏目管理页面.
//栏目中用到几个全局变量
function kindsStat($parent_id,$deepID)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	GLOBAL $table;
	GLOBAL $posts_all;
	GLOBAL $hits_all;
	GLOBAL $comments_all;
	
		
	$sql = "select id,order_id,name,folder,pagesize,template,parent_id from ".$config['tablePre']."kinds where parent_id=".$parent_id." order by order_id";
	$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

	//行号,需要带入递归,所以为静态变量.
	STATIC $rowmum = 1;

	$deepID++;
	while(list($id,$order_id,$name,$folder,$pagesize,$template,$parent_id) = $result->fetch_row())
	{
	$name = ($parent_id==0)?"<span style=\"font-weight:bold\">".$name."</span>":$name;
	$subkinds = kindsGetChildren($id);
	$sql_post = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where kind_id in (".$subkinds.") and is_show";
	$result_post = $mysqli->query($sql_post);
	list($posts,$hits,$comments) = $result_post->fetch_row();
	$progress_posts = (round($posts/$posts_all*100,2))."%";
	$progress_hits = (round($hits/$hits_all*100,2))."%";
	$progress_comments = (round($comments/$comments_all*100,2))."%";
	
	
	
	echo('<tr>');
	printf('<td style="text-align:left;"><div style="width:%dpx;height:20px;background:url(assets/images/bg_kinds.gif) right;float:left;"></div>%s</td>',($deepID*50-40),$name);
	printf('<td>%d</td>',$posts);
	printf('<td>%d</td>',$hits);
	printf('<td>%d</td>',$comments);
	printf('<td>%s</td>',$progress_posts);
	printf('<td>%s</td>',$progress_hits);
	printf('<td>%s</td>',$progress_comments);
	printf('<td>%s</td>',round($hits/$posts,2));
	printf('<td>%s</td>',round($comments/$posts,2));
	echo('</tr>');
	
	
	$rowmum++;
	
	if ( kindsHasChild($id))
		{
		kindsStat($id,$deepID);
		}

	}
	$result->free();
	return $rowmum;
}

?>
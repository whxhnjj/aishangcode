<?php
require_once("../includes/config.php");
require_once("../includes/rights.php");
require_once("../includes/function.php");
require_once("../includes/conn.php");

$title = iconv("UTF-8","gbk",$_POST['title']);
$keyword = iconv("UTF-8","gbk",$_POST['keyword']);

//$title = iconv("UTF-8","gbk",$_GET['title']);
//$keyword = iconv("UTF-8","gbk",$_GET['keyword']);

$arr_keywords[] = $keyword;

$sql = "select name from ".$config['tablePre']."keywords";
$result = $mysqli->query($sql);
while ($key = $result->fetch_row())
{
	if (substr_count($title,$key[0]) > 0 and substr_count(",".$keyword.",",",".$key[0].",") == 0)
	{
	$arr_keywords[] = $key[0];
	}
}


if (isset($arr_keywords))
{
$new_keywords = trim(implode(",",$arr_keywords),',');
echo($new_keywords);
}
?>
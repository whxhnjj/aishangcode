<?php
require_once(dirname(__FILE__)."/../includes/config.php");
require_once(dirname(__FILE__)."/../includes/function.php");
require_once(dirname(__FILE__)."/../includes/conn.php");


$sql = "select id,title,dateline from ".$config['tablePre']."posts where special=0 order by id desc";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$i = 0;
while(list($id,$title,$dateline) = $result->fetch_row())
{
	//调用写入单文章函数
	$writeShowHtml = writeShowHtml($id,$dateline);
	//if ($writeShowHtml>0) printf ("%d.%s<br />",$id,$title);
	$i++;
	printf('%d\n',$writeShowHtml);
}
$result->free();

printf('%d条生成。',$i);
?>
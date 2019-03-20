<?php
ignore_user_abort(); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去

//写入时间标记文件
$targetPath = $_SERVER['DOCUMENT_ROOT'].$config['basePath']."data/";
$file=fopen($targetPath."last_auto_write_html.txt","w+");
fwrite($file,time());
fclose($file);

//写文章页
$sql = "select id,title,dateline from ".$config['tablePre']."posts order by id desc";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

while(list($id,$title,$dateline) = $result->fetch_row())
{
	//调用写入单文章函数
	$writeShowHtml = writeShowHtml($id,$dateline);
}
$result->free();


//写栏目页
$result = $mysqli->query("select id,folder,pagesize from ".$config['tablePre']."kinds where parent_id>0 and folder<>'' order by id");
while(list($kind_id,$folder,$pagesize) = $result->fetch_row())
{
	//确定总记录数，并算出总页数。
	list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where kind_id=".$kind_id)->fetch_row();
	$pagecount = ceil($recoudcount/$pagesize);
	if ($pagecount == 0) $pagecount = 1;

	//本次输出的开始页与结束页
	$i_start = 1;
	$i_end = $pagecount;

	for ($i=$i_start;$i<=$i_end;$i++)
	{
		$writeListHtml = writeListHtml($kind_id,$folder,$i);
	}
}

//写其它页
writePhpToHtml();
?>
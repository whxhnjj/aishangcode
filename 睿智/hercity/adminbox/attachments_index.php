<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

//确定分页所需要三个元素：当前所在页，每页记录数，总记录数。
$page = _GET('page','i',1);
$pagesize = 20;
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."attachments")->fetch_row();

//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 附件管理 -> 附件列表");

echo ("<ul id=\"tools\">");
echo ("<li><a id=\"attachments_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>\n\n");




//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th>标题</th>');
echo('<th style="width:80px;">所属文章ID</th>');
echo('<th style="width:200px;">文件名</th>');
echo('<th style="width:80px;">文件大小</th>');
echo('<th style="width:200px;">文件类型</th>');
echo('<th style="width:80px;">是否图片</th>');
echo('<th style="width:80px;">修改</th>');
echo('</tr>');


$sql = "select id,post_id,title,filename,filesize,filetype,dateline,is_img from ".$config['tablePre']."attachments order by id desc limit ".($page-1)*$pagesize.",".$pagesize;
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$rowmum=1;
while(list($id,$post_id,$title,$filename,$filesize,$filetype,$dateline,$is_img) = $result->fetch_row())
{
echo('<tr>');
printf('<td><input type="checkbox" class="checkitem" value="%d" /></td>',$id);
printf('<td>%d</td>',$id);
printf('<td>%s</td>',$title);
printf('<td>%d</td>',$post_id);
printf('<td style="text-align:left;"><a href="%s" target="_blank">%s</a></td>',$config['basePath'].$config['attachmentPath'].$filename,$filename);
printf('<td>%s</td>',round($filesize/1024,2)."k");
printf('<td>%s</td>',$filetype);
printf('<td>%s</td>',$is_img);
printf('<td><a href="attachments_edit.php?id=%d">修改</a></td>',$id);
echo('</tr>');
}
echo('</table>');











$result->free();

//调用分页函数
echo (pages($recoudcount,$pagesize,$page));

$mysqli->close();
require_once("includes/footer.php");
?>
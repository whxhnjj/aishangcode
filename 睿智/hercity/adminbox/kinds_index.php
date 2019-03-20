<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");
?>

<script type="text/javascript">
<!--
$(function(){

$("#kinds_order").click( function(){
$("#form1").submit();
});

});

//-->
</script>

<?php
//引导栏
echo ("<div id=\"path\">");
echo ("loftCMS -> 栏目管理 -> 栏目列表");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"kinds_new.php\">新建频道</a></li>");
printf ("<li><a id=\"kinds_order\">修改排序</a></li>");
echo ("<li><a id=\"kinds_list\" class=\"todo\">生成列表页</a></li>");
echo ("<li><a id=\"kinds_not_list\" class=\"todo\">不生成列表页</a></li>");
echo ("<li><a id=\"kinds_del\" class=\"todo\">删除</a></li>");
echo ("</ul>");

echo ("</div>");



echo "<form name=\"form1\" id=\"form1\" action=\"kinds_order.php\" method=\"post\">\n";

//写表格
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:60px;">排序</th>');
echo('<th style="text-align:left;">栏目名称</th>');
echo('<th style="text-align:left;width:80px;">栏目文件夹</th>');
echo('<th style="width:60px;">分页容量</th>');
echo('<th style="width:120px;">模板文件</th>');
echo('<th style="width:80px;">生成列表页?</th>');
echo('<th style="width:40px;">生成</th>');
echo('<th style="width:40px;">修改</th>');
echo('</tr>');

//调用listKinds方法递归列出栏目
kindsTableList(0,0);

echo('</table>');
echo "</form>\n";



$mysqli->close();
require_once("includes/footer.php");
?>
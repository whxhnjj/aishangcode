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
//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ��Ŀ���� -> ��Ŀ�б�");

echo ("<ul id=\"tools\">");
printf ("<li><a href=\"kinds_new.php\">�½�Ƶ��</a></li>");
printf ("<li><a id=\"kinds_order\">�޸�����</a></li>");
echo ("<li><a id=\"kinds_list\" class=\"todo\">�����б�ҳ</a></li>");
echo ("<li><a id=\"kinds_not_list\" class=\"todo\">�������б�ҳ</a></li>");
echo ("<li><a id=\"kinds_del\" class=\"todo\">ɾ��</a></li>");
echo ("</ul>");

echo ("</div>");



echo "<form name=\"form1\" id=\"form1\" action=\"kinds_order.php\" method=\"post\">\n";

//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:20px;"><input type="checkbox" class="checkall" /></th>');
echo('<th style="width:60px;">ID</th>');
echo('<th style="width:60px;">����</th>');
echo('<th style="text-align:left;">��Ŀ����</th>');
echo('<th style="text-align:left;width:80px;">��Ŀ�ļ���</th>');
echo('<th style="width:60px;">��ҳ����</th>');
echo('<th style="width:120px;">ģ���ļ�</th>');
echo('<th style="width:80px;">�����б�ҳ?</th>');
echo('<th style="width:40px;">����</th>');
echo('<th style="width:40px;">�޸�</th>');
echo('</tr>');

//����listKinds�����ݹ��г���Ŀ
kindsTableList(0,0);

echo('</table>');
echo "</form>\n";



$mysqli->close();
require_once("includes/footer.php");
?>
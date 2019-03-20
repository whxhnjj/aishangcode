<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


$username = _GET('username','s','muzili');
$month = _GET('month','s',date('Ym'));

if (_GET('format')=='excel')
{
header("Content-Type: application/vnd.ms-excel; charset=UTF-8"); 
header("Content-Disposition: attachment;filename=system_logstats_".date("Ymd").".xls ");    
header("Content-Transfer-Encoding: binary ");
}
else

{
//������
echo ("<div id=\"path\">");
echo ("loftCMS -> ϵͳ���� -> ���ڼ�¼");
echo ("<ul id=\"tools\">");
printf ("<li><a href=\"?format=excel\">����Excel</a></li>");
echo ("</ul>");
echo ("</div>");
}

echo ('<div class="searchBox">');

//��ȡ�·�
echo ('ѡ���·ݣ�');
for ($m = 0;$m > -12;$m--)
{
$themonth = date('Ym',strtotime($m.' month'));
$class = ($themonth == $month) ? 'class="red"' : '';
printf ('<a href="?month=%s&username=%s" %s>%d</a>&nbsp;',$themonth,$username,$class,$m);
}

//��ȡ�û�
echo ('&nbsp;&nbsp;&nbsp;&nbsp;ѡ���û���');
$sql = "select uname,nickname from ".$config['tablePre']."users where is_active order by id";
$result = $mysqli->query($sql);
while(list($uname,$nickname) = $result->fetch_row())
{
$class = ($username == $uname) ? 'class="red"' : '';
printf ('<a href="?month=%s&username=%s" %s>%s</a>&nbsp;',$month,$uname,$class,$nickname);
}

echo ("</div>");





//д���
echo('<table cellspacing="1" cellpadding="2" class="dataTable">');
echo('<tr>');
echo('<th style="width:140px;">����</th>');
echo('<th>ϵͳ</th>');
echo('<th style="width:150px;">IP</th>');
echo('<th style="width:90px;">ʱ��</th>');
echo('</tr>');


$sql = "select dateline,ip,sys from ".$config['tablePre']."logs where DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y%m')=".$month." and is_success and uname='".$username."' order by dateline";
$result = $mysqli->query($sql);

$rowmum=1;
$latetimes = 0;
$week = array('��','һ','��','��','��','��','��');
while(list($dateline,$ip,$sys) = $result->fetch_row())
{
	if($dt != date('Y-m-d',$dateline))
	{
	$dt = date('Y-m-d',$dateline);
	$w = date('w',$dateline);
	$date = $dt.'(����'.$week[$w].')';
	if ($w == 0 || $w == 6 ){$date = '<span class="green">'.$date.'</span>';}
	$time = date('H:i:s',$dateline);
	if ($time > '09:00:00' && $w > 0 && $w <6)
	{
		$time = '<span class="red">'.$time.'</span>';
		$latetimes ++;
	}
	$arr_sys = explode(';',$sys);
	$sys = '<span title="'.$sys.'">'.substr($sys,0,80).'��</span>';

	echo('<tr>');
	printf('<td>%s</td>',$date);
	printf('<td>%s</td>',$sys);
	printf('<td>%s</td>',$ip);
	printf('<td>%s</td>',$time);
	echo('</tr>');
	}
}

echo('<tr>');
printf('<td>�ϼ�</td>');
printf('<td></td>');
printf('<td></td>');
printf('<td>�ٵ�%d��</td>',$latetimes);
echo('</tr>');


echo('</table>');







$mysqli->close();
require_once("includes/footer.php");
?>
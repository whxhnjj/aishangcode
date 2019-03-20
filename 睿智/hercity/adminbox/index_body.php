<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$uid = $_SESSION['userid'];
$uname = $_SESSION['username'];

//�û���Ϣ
$sql = "select logintimes,point from ".$config['tablePre']."users where id = ".$uid;
$result = $mysqli->query($sql);
list($logintimes,$point) = $result->fetch_row();

//ȡ����༭��
$sql = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where is_show and user_id = ".$uid." and DATE_FORMAT(FROM_UNIXTIME(dateline),'%Y-%m-%d') = CURDATE()";
$result = $mysqli->query($sql);
list($posts_today,$hits_today,$comments_today) = $result->fetch_row();

//ȡ30��༭��
$sql = "select count(id),sum(hits),sum(comments) from ".$config['tablePre']."posts where is_show and user_id = ".$uid." and dateline > UNIX_TIMESTAMP()-2679120";
$result = $mysqli->query($sql);
list($posts_30days,$hits_30days,$comments_30days) = $result->fetch_row();

//ȡ���ݿ�ߴ�
$result = $mysqli->query("SHOW TABLE STATUS FROM ".$config['dbName']);
$database_size = "";
while($rs = $result->fetch_row())
{
$database_size += $rs[6]+$rs[8];
}



printf("<h2>%s ��������</h2>",$config['siteName']);

printf("<h4>������ʾ</h4>");
printf("<span class=\"red\">%s</span>(UID:%d)�����ã�<br />",$uname,$uid);
printf("��������<span class=\"orange\">(%d)</span>�ε�¼�����ۻ����<span class=\"orange\">(%d)</span>���ƹ���֡�<br />",$logintimes,$point);
printf("�����칲��������<span class=\"orange\">(%d)</span>ƪ����Щ����Ŀǰ�������<span class=\"orange\">(%d)</span>�˴Σ���������<span class=\"orange\">(%d)</span>�˴Ρ�<br />",$posts_today,$hits_today,$comments_today);
printf("��30���ڷ�������<span class=\"orange\">(%d)</span>ƪ����Щ����Ŀǰ�������<span class=\"orange\">(%d)</span>�˴Σ���������<span class=\"orange\">(%d)</span>�˴Ρ�",$posts_30days,$hits_30days,$comments_30days);

printf("<h4>ϵͳ��Ϣ</h4>");
printf("��ǰ����汾��%s<br />",$config['softVersion']);
printf("��������£�%s<br />",$config['softUpdate']);
printf("��ǰ���ݿ�ߴ磺%s M<br />",round($database_size/1024/1024,2));

printf("<h4>ʱ����Ϣ</h4>");
printf("���ε�¼ʱ�䣺%s<br />",date('Y-m-d H:i:s',$_SESSION['userlogintime']));



require_once("includes/footer.php");
?>
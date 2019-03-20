<?php
require_once("../includes/interface.php");

$id = _GET('id','i');
$sql = "update ".$config['tablePre']."posts set hits=hits+1 where id=".$id;
$mysqli->query($sql);

$sql = "select linkto from ".$config['tablePre']."posts where id=".$id;
if ($result = $mysqli->query($sql))
{
list($linkto) = $result->fetch_row();
if ($linkto == '') $linkto = "/";
}


//若是淘客，需用js跳转，否则转向淘宝首页了。若是其它，用form方式，可被统计到。
if (strpos($linkto,'taobao.com'))
{
?>
<script type="text/javascript">
window.location.href = "<?=$linkto?>";
</script>
<?php
}
else
{
?>
<html>
<body onload="document.form1.submit()">
<form name="form1" method="post" action="<?=$linkto?>"></form>
</body>
</html>
<?php
}
?>
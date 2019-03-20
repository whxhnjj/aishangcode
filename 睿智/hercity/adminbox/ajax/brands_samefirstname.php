<?php
require_once("../includes/config.php");
require_once("../includes/rights.php");
require_once("../includes/function.php");
require_once("../includes/conn.php");

$firstname = iconv("UTF-8","gb2312",$_POST['firstname']);
$id = _POST('id','i',0);
$j = 0;

if ($firstname)
{
	$sql = "select count(id) from ".$config['tablePre']."brands where firstname = '".$firstname."' and id <> ".$id;
	list($i) = $mysqli->query($sql)->fetch_row();
	if ($i){$j += 1;}
}

echo $j;
?>
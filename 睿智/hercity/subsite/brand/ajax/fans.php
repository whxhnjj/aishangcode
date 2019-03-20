<?php
require_once("../includes/config.php");
require_once("../includes/function.php");
require_once("../includes/conn.php");

$id = _POST('id','i');

session_start();
$session_tag = isset($_SESSION["brand_fans".$id]) ? $_SESSION["brand_fans".$id] : 0;

if (time() - $session_tag > 60)
{
$sql = "update ".$config['tablePre']."brands set fans=fans+1 where id=".$id;
$mysqli->query($sql);
$_SESSION["brand_fans".$id] = time();
}
?>
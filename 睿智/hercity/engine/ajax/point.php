<?php
require_once("../includes/interface.php");

$id = _POST('id','i');

session_start();
$session_tag = isset($_SESSION["postpoint".$id]) ? $_SESSION["postpoint".$id] : 0;

if (time() - $session_tag > 60)
{
$i = rand(1,5);
$sql = "update ".$config['tablePre']."posts set point=point+".$i." where id=".$id;
$mysqli->query($sql);
$_SESSION["postpoint".$id] = time();
echo '1';
}
?>
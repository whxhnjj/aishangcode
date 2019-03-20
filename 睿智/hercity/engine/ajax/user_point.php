<?php
require_once("../includes/interface.php");

$id = _POST('id','i');

session_start();
$session_tag = isset($_SESSION["userpoint".$id]) ? $_SESSION["userpoint".$id] : 0;

if (time() - $session_tag > 60 && $id != $_SESSION['userid'])
{
$sql = "update ".$config['tablePre']."users set point=point+1 where id=".$id;
$mysqli->query($sql);
$_SESSION["userpoint".$id] = time();
}
?>
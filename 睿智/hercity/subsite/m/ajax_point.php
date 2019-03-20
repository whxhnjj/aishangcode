<?php
require_once('ini.php');

$id = $_POST['id'];

session_start();
$session_tag = isset($_SESSION["postpoint".$id]) ? $_SESSION["postpoint".$id] : 0;

if (time() - $session_tag > 60)
{
$data = setPoint($id);
$_SESSION["postpoint".$id] = time();
echo $data;
}
?>
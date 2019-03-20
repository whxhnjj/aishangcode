<?php
require_once("../includes/interface.php");
$comment_id = _POST('comment_id','i');
$sql = "update ".$config['tablePre']."comments set point=point+1 where id=".$comment_id;
$mysqli->query($sql);
?>
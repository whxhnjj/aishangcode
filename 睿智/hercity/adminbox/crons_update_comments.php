<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$sql = "select id from ".$config['tablePre']."posts order by id desc";
$result = $mysqli->query($sql);
while (list($id) = $result->fetch_row())
{
$sql = "update ".$config['tablePre']."posts set comments = (select count(*) from ".$config['tablePre']."comments where post_id = ".$id.") where id=".$id;
$mysqli->query($sql);
}
echo ("ȫ���������!<a href=\"crons_update_index.php\">����</a>");

require_once("includes/footer.php");
?>
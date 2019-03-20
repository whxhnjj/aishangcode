<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$order_id=_POST('order_id','ai');

while ($a = each($order_id))
{
$sql = "update ".$config['tablePre']."kinds set order_id = ".$a['value']." where id=".$a['key'];
$result = $mysqli->query($sql);
}

$url="kinds_index.php";
redirect("栏目排序修改成功",$url);
require_once("includes/footer.php");
?>
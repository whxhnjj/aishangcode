<?php
require_once("includes/interface.php");



$appkey = _GET('appkey');

if ($appkey == '6de5e05dbccd474fb60cb3498122aead')
{
$where = _GET('where','s','1=1');
$where = html_entity_decode(str_replace('\\\'','\'',urldecode($where)));
if ($where != '') $where = 'and ('.$where.')';
$order = _GET('order','s','id desc');
$order = urldecode($order);
if ($order == '') $order = 'id desc';
$limit = _GET('limit','s','10');
$fields = _GET('fields','s','');
if ($fields == '') $fields = 'id,name';

$list = getArrayToJsonFromKinds($fields,"where 1=1 ".$where." order by ".$order." limit ".$limit);
$json = json_encode($list);
echo $json;
}
else
{
echo '错误的appkey。';
}


$mysqli->close();


//根据指定的子句读文章并返回一个数组
function getArrayToJsonFromKinds($fields,$sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$arr_fields = explode(',',$fields);
	$sql = "select ".$fields." from ".$config['tablePre']."kinds ".$sql;
	if ($result = $mysqli->query($sql))
	{
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		if (in_array('name',$arr_fields)) {$row['name'] = gbkToUtf8($row['name']); }
		if (in_array('title',$arr_fields)) {$row['title'] = gbkToUtf8($row['title']); }
		if (in_array('keywords',$arr_fields)) {$row['keywords'] = gbkToUtf8($row['keywords']); }
		if (in_array('description',$arr_fields)) {$row['description'] = gbkToUtf8($row['description']); }
		$list[] = $row;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}
?>
<?php
require_once("includes/interface.php");

$appkey = _GET('appkey');

if ($appkey == '6de5e05dbccd474fb60cb3498122aead')
{
$where = _GET('where','s','1=1');
$where = html_entity_decode(str_replace('\\\'','\'',urldecode($where)));
if ($where != '') $where = 'and ('.$where.')';
$order = _GET('order','s','order_id desc,id desc');
$order = urldecode($order);
if ($order == '') $order = 'id desc';
$limit = _GET('limit','s','10');
$fields = _GET('fields','s','');
if ($fields == '') $fields = 'id,title';

$list = getArrayToJsonFromPosts($fields,"where is_show and dateline<=UNIX_TIMESTAMP() ".$where." order by ".$order." limit ".$limit);
$json = json_encode($list);
echo $json;
}
else
{
echo '错误的appkey。';
}


$mysqli->close();


//根据指定的子句读文章并返回一个数组
function getArrayToJsonFromPosts($fields,$sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$arr_fields = explode(',',$fields);

	$fields = str_replace(',content','',$fields);
	$fields = str_replace('content,','',$fields);

	$sql = "select ".$fields." from ".$config['tablePre']."posts ".$sql;
	if ($result = $mysqli->query($sql))
	{
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		if (in_array('title',$arr_fields)) {$row['title'] = gbkToUtf8($row['title']); }
		if (in_array('subhead1',$arr_fields)) {$row['subhead1'] = gbkToUtf8($row['subhead1']); }
		if (in_array('subhead2',$arr_fields)) {$row['subhead2'] = gbkToUtf8($row['subhead2']); }
		if (in_array('brief',$arr_fields)) {$row['brief'] = gbkToUtf8($row['brief']); }
		if (in_array('author',$arr_fields)) {$row['author'] = gbkToUtf8($row['author']); }
		if (in_array('sourse',$arr_fields)) {$row['sourse'] = gbkToUtf8($row['sourse']); }
		if (in_array('thumb1',$arr_fields)) {$row['thumb1'] = $config['thumbPath'].$row['thumb1']; }
		if (in_array('thumb2',$arr_fields)) {$row['thumb2'] = $config['thumbPath'].$row['thumb2']; }
		if (in_array('content',$arr_fields)) {
			$sql = "select content from ".$config['tablePre']."postcontents where post_id = ".$row['id'];
			list($content) =$mysqli->query($sql)->fetch_row() or $content='';
			$row['content'] = gbkToUtf8($content);
		}

		$list[] = $row;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}
?>
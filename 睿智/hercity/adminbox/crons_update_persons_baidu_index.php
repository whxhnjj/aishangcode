<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");


set_time_limit(120);

//本批处理的起始点
$i = _GET('i','i',0);

//每批写的文件数
$writecount = 10;

//读取总记录数
list($recoudcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."persons")->fetch_row();

$sql = "select id,name from ".$config['tablePre']."persons order by id desc limit $i,$writecount";
$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

$i_start = $i+1;
$i_end = (($i+$writecount)>$recoudcount)?$recoudcount:($i+$writecount);
$progress = (ceil($i_start/$recoudcount*100))."%";
printf("共%d条记录，当前正在处理第%d到%d条...(%s)<hr />",$recoudcount,$i_start,$i_end,$progress);

while(list($id,$name) = $result->fetch_row())
{
	$baidu_index = getBaiduIndex($name);
	$sql = "update ".$config['tablePre']."persons set baidu_index = ".$baidu_index."+(fans*10)+hits where id=".$id;
	$mysqli->query($sql);
	printf("%d.%s(%d)更新完成。<br />",$id,$name,$baidu_index);
	sleep(1);
}
$result->free();

$i += $writecount;

if ($i < $recoudcount)
{
printf("<hr />请等待，页面将自动跳转。若不能自动跳转请<a href=\"%s?i=%d\">点击这里</a>",$_SERVER['PHP_SELF'],$i);
printf("<script type=\"text/javascript\">window.location=\"%s?i=%d\";</script>",$_SERVER['PHP_SELF'],$i);
}
else
{
echo("全部处理完成!<a href=\"crons_update_index.php\">返回</a>");
}

require_once("includes/footer.php");



function getBaiduIndex($name)
{

	$name = strtolower($name);
	$name_utf8 = mb_convert_encoding($name, 'utf-8', 'gbk');
	$name_utf8_encode = formatSting($name_utf8); // urlencode($name_utf8);

	//读7c.com
	/*
	$url = 'http://www.7c.com/keyword/'.$name_utf8_encode.'/';
	$modifier[0] = '/<td.*?><a href="http:\/\/www\.baidu\.com\/baidu\?word='.$name.'".*?>'.$name.'<\/a><\/td>[\w\W]*?<td.*?>([0-9]+)<\/td>/';
	$modifier[1] = '/<td.*?><a href="http:\/\/www\.baidu\.com\/baidu\?word=.*?'.$name.'.*?".*?>.*?'.$name.'.*?<\/a><\/td>[\w\W]*?<td.*?>([0-9]+)<\/td>/';
	*/

	//读aizhan.com
	$url = 'https://ci.aizhan.com/'.$name_utf8_encode.'/';
	// $modifier[0] = '/'.$name_utf8.'<\/font><\/a><\/td>[\w\W]*?<td align="right"><?([0-9]+)<\/td>/'; //随时根据aizhan变更修改此处。
	$modifier = '/<span class="blue">(&lt;)?([0-9]+) \/ (&lt;)?([0-9]+)<\/span>/';




	//伪装不同IP
	$ip = getRandIp();
	$headers['CLIENT-IP'] = $ip;
	$headers['X-FORWARDED-FOR'] = $ip;
	$headerArr = array();
	foreach( $headers as $n => $v ) {
		$headerArr[] = $n .':' . $v;
	}


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	$file_content = curl_exec($ch);

	preg_match_all($modifier, $file_content, $b);

	curl_close($ch);

	$count = 0;
	foreach ($b[0] as $k => $v) {
		if ($b[1][$k] == '') {
			$count += $b[2][$k];
		} else {
			$count += 25;
		}
		if ($b[3][$k] == '') {
			$count += $b[4][$k];
		} else {
			$count += 25;
		}
	}
	return $count;
}


/**
 * 爱站关键词挖掘-关键词格式化
 * @param string $string
 * @return string
 */
function formatSting(string $string)
{
    $array = preg_split('//u', $string, -1);
    array_shift($array);
    array_pop($array);
    $result = '';
    foreach ($array as $value) {
        if (mb_strlen($value) != strlen($value)) {
            $result .= str_replace('\\u', '', substr(json_encode($value), 3, -1));
        } else {
            $result .= 'n'.bin2hex($value);
        }
    }
    return $result;
}

function getRandIp()
{
	$ip2_arr=array('116.16','218.15','61.146','119.125','125.89','116.5','61.134','211.122','117.85','218.30','101.11','120,18');
	$ip2=$ip2_arr[array_rand($ip2_arr)];
	$ip3=rand(1,250);
	$ip4=rand(1,250);
	$myip = $ip2.".".$ip3.".".$ip4;
	return $myip;
}
?>

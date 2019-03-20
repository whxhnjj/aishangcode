<?php
//自动加载类的函数
function __autoload($class)
{
	//GLOBAL $config;
	include_once(dirname(__FILE__)."/../classes/$class.class.php");
}


//kindsHasChild函数
//作用：判断一个栏目只否有子栏目
//$mysqli数据库连接对象;$dbTable栏目数据表名;$kindID栏目ID
function kindsHasChild($kindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$result = $mysqli->query("select count(id) as count from ".$config['tablePre']."kinds where parent_id=".$kindID." ");
	list($recoudcount)=$result->fetch_row();
	$hasChild=$recoudcount>0?true:false;
	return $hasChild;
}


//kindsGetInfo函数
//作用：获取一个栏目的信息，返回一个数组。
//$mysqli数据库连接对象;$dbTable栏目数据表名;$kindID栏目ID;
function kindsGetInfo($kindID=0,$f='')
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,name,linkto,folder,template,showtemplate,pagesize,parent_id,order_id from ".$config['tablePre']."kinds where id=".$kindID." ";
	$result = $mysqli->query($sql);
	$k = $result->fetch_array();
	if($f=='')
	{
		$kindsGetInfo = array("id" => $k[0],"name" => $k[1],"linkto" => $k[2],"folder" => $k[3],"template" => $k[4],"showtemplate" => $k[5],"pagesize" => $k[6],"parent_id" => $k[7],"order_id" => $k[8]);
	}
	else
	{
		$kindsGetInfo = $k[$f];
	}
return $kindsGetInfo;
}

//kindsGetChildren
//作用：获取一个栏目下的所有子栏目ID。返回一个字符串。
function kindsGetChildren($kindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$kinds[] = $kindID;
	$sql = "select id from ".$config['tablePre']."kinds where parent_id=".$kindID;
	$result = $mysqli->query($sql);
	$str_kinds = $kindID;
	while(list($id) = $result->fetch_row())
	{
		$str_kinds = $str_kinds.",".kindsGetChildren($id);
	}
	return $str_kinds;
}

//kindsGetTop
//作用：获取一个栏目的顶级父栏目ID。返回一个数值。
function kindsGetTop($kindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select * from ".$config['tablePre']."kinds where id=".$kindID;
	$result = $mysqli->query($sql);
	$parent = $result->fetch_array();
	if ($parent['parent_id'] > 0)
	{
		$top_kind = kindsGetTop($parent['parent_id']);
	}
	else
	{
		$top_kind = $parent;
	}	
	return $top_kind;
}



//kindsTreeList函数
//作用：递归列出栏目,转化成<ul><li>列表
//$mysqli数据库连接对象;$dbTable栏目数据表名
function kindsTreeList($kindID,$baseID,$linkto,$strSelectKindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$str_where=($strSelectKindID<>0)?" and id in (".$strSelectKindID.")":"";
	$sql = "select id,name from ".$config['tablePre']."kinds where parent_id=".$kindID.$str_where." order by order_id,id";
	$result = $mysqli->query($sql);

	echo("<ul>\n");
	while(list($kindID,$name) = $result->fetch_row())
	{
		$treeID=$baseID."_".$kindID;

		if (! kindsHasChild($kindID))
			{
			printf("<li id=\"li_%s\" data=\"url:'%s',target:'main'\">%s</li>\n",$treeID,$linkto.$kindID,$name);
			}
		else
			{
			printf("<li class=\"folder\">%s\n",$name);
			kindsTreeList($kindID,$treeID,$linkto,$strSelectKindID);
			printf("</li>\n");
			}
	}
	echo("</ul>\n");

	$result->free();
}



//递归列出栏目,转化为Select列表框
Function kindsSelectList($kindID=0,$currentKindID=0,$deepID=0,$strSelectKindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$str_where=($strSelectKindID<>0)?" and id in (".$strSelectKindID.")":"";
	$sql = "select id,name from ".$config['tablePre']."kinds where parent_id=".$kindID.$str_where." order by order_id,id";
	$result = $mysqli->query($sql);

	$deepID++;
	while(list($kindID,$name) = $result->fetch_row())
	{
		$name = str_pad(" ",$deepID,"-").$name;
		$selected = $currentKindID == $kindID ? "selected=\"selected\" class=\"selected\" " : "";

		if (! kindsHasChild($kindID))
			{
			printf("<option value=\"%d\" %s >%s</option>\n",$kindID,$selected,$name);
			}
		else
			{
			printf("<option value=\"0\" class=\"inactive\" >%s</option>\n",$name);
			kindsSelectList($kindID,$currentKindID,$deepID,$strSelectKindID);
			}
	}

	$result->free();
}



//kindsTableList
//递归列出栏目,用于栏目管理页面.
//栏目中用到几个全局变量
function kindsTableList($parent_id,$deepID)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	GLOBAL $table;
	$sql = "select id,order_id,name,folder,pagesize,template,is_list,parent_id from ".$config['tablePre']."kinds where parent_id=".$parent_id." order by order_id,id";
	$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

	//行号,需要带入递归,所以为静态变量.
	STATIC $rowmum = 1;

	$deepID++;
	while(list($id,$order_id,$name,$folder,$pagesize,$template,$is_list,$parent_id) = $result->fetch_row())
	{
	$name = ($parent_id==0)?"<span style=\"font-weight:bold\">".$name."</span>":$name;
	$is_list_html = $is_list ? "<span class=\"green\">是</span>" : "<span>否</span>";
	
	
	echo('<tr>');
	printf('<td>%s</td>',kindsHasChild($id)?"":"<input type=\"checkbox\" class=\"checkitem\" value=\"$id\" /><a id=\"k$id\"></a>");
	printf('<td>%d</td>',$id);
	printf('<td><input type="text" name="order_id[%d]" value="%d" size="3" /></td>',$id,$order_id);
	printf('<td style="text-align:left;"><div style="width:%dpx;height:20px;background:url(images/bg_kinds.gif) right;float:left;"></div>%s <a style="color:#ccc" href="kinds_new.php?parent_id=%d">[新建子栏目]</a></td>',($deepID*50-40),$name,$id);
	printf('<td style="text-align:left;">%s</td>',$folder);
	printf('<td>%d</td>',$pagesize);
	printf('<td>%s</td>',$template);
	printf('<td>%s</td>',$is_list_html);
	printf('<td>%s</td>',$is_list?'<a href="posts_write_list.php?kind_id='.$id.'">生成</a>':'');	
	printf('<td><a href="kinds_edit.php?id=%d">修改</a></td>',$id);
	echo('</tr>');


	
	$rowmum++;
	
	if ( kindsHasChild($id))
		{
		kindsTableList($id,$deepID);
		}

	}
	$result->free();
}



//数组转化为select列表的option
//$array是要转化的数组
//$value与$text是指<option value="$value">$text</option>，可取值0或1，0是指该位置填充数组的键，1是指该位置填充数组的值。
//$default是字默认选中的项目，可以一项也可以是逗号隔开的多项如："1,2,3"，'abc,abd,ebd'。
//$default_type是指$default的类型(键或值)，默认是0(键)。
function arrayToOptions($array,$value=0,$text=1,$default='',$default_type=0)
{
	while($item = each($array))
	{
	$selected = (substr_count(",".$default.",",",".$item[$default_type].",")>0 && $default!='') ? "selected=\"selected\" class=\"selected\" " : "";
	printf("<option value=\"%s\" %s>%s</option>\n",$item[$value],$selected,$item[$text]);
	}
}


//数组转化为checkbox
//$name是指checkbox的名称
//$array是要转化的数组
//$value与$text是定义checkbox的值与显示的文字由什么来填充，可取值0或1，0是指该位置填充数组的键，1是指该位置填充数组的值。
//$default是字默认选中的项目，可以一项也可以是逗号隔开的多项如："1,2,3"，'abc,abd,ebd'。
//$default_type是指$default的类型(键或值)，默认是0(键)。
//$id_prefix是指checkbox与label的id值的前缀，当出现多循环调用时，用以区分id.
function arrayToCheckbox($name,$array,$value=0,$text=1,$default='',$default_type=0,$id_prefix='')
{
	$i = 1;
	while($item = each($array))
	{
	$checked = (substr_count(",".$default.",",",".$item[$default_type].",")>0 && $default!='') ? "checked=\"checked\" class=\"checked\" " : "";
	$id = $name.'_'.$id_prefix.'_'.$i;
	printf("<input type=\"checkbox\" name=\"%s[]\" id=\"%s\" value=\"%s\" %s /><label for=\"%s\">%s</label>\n",$name,$id,$item[$value],$checked,$id,$item[$text]);
	$i++;
	}
}




//跳转页面
function redirect($message,$url)
{
	echo ("<html>");
	echo ("<head>");
	echo ("<meta http-equiv=\"content-type\" content=\"text/html; charset=gb2312\" />");
	printf("<meta http-equiv=\"refresh\" content=\"1; url=%s\" />",$url);
	echo ("<link rel=\"stylesheet\" href=\"../images/main.css\" type=\"text/css\" />");
	echo ("</head>");
	echo ("<body style=\"text-align:center;\">");
	printf("<div class=\"messagebox\"><strong>%s</strong>，将自动跳转……<br />如果不能自动跳转，请<a href=\"%s\">点击这里</a>。",$message,$url);
	echo ("</div>");
	echo ("</body>");
	echo ("</html>");
}



//分页
function pages($recoudcount,$pagesize=20,$page=1,$url="")
{
	$pagecount = ceil($recoudcount/$pagesize);
	$querystring = preg_replace("/page=[0-9]+&/","",$_SERVER['QUERY_STRING']."&");

	//若没有传入$url,则自动生成$url如http://www.abc.com/abc/list.php?kind=1&page=%d,%d代表页码
	if ($url == "") $url = $_SERVER['PHP_SELF']."?".str_replace('%','%%',$querystring)."page=%d";

	$pages = "<div class=\"pages\">";

	if ($page>1) $pages .= sprintf("<a href=\"".$url."\">&lt;</a>",($page-1));
	$pages .= sprintf("<a href=\"".$url."\"%s>1</a>","1",(1==$page)?" class=\"current\" ":"");
	for ($i = 2;$i < $pagecount;$i++)
	{
	if (abs($i-$page)<5)
	{
	$pages .= sprintf("<a href=\"".$url."\"%s>%s</a>",$i,($i==$page)?" class=\"current\" ":"",$i);
	}
	else
	{
	if (abs($i-$page)<6) $pages .= "......";
	}
	}
	if ($pagecount>1) $pages .= sprintf("<a href=\"".$url."\"%s>%s</a>",$pagecount,($pagecount==$page)?" class=\"current\" ":"",$pagecount);
	if ($page<$pagecount) $pages .= sprintf("<a href=\"".$url."\">&gt;</a>",($page+1));
	$pages .= "</div>\n";
	
	return $pages;
}



//根据文章ID得到文章html文件的路径

function getPostPath($id,$dateline='')
{
	GLOBAL $config;
	GLOBAL $mysqli;
	
	if (strpos($config['htmlShowPath'],'%s'))
	{
		$sql = "select p.dateline,k.folder from ".$config['tablePre']."kinds as k,".$config['tablePre']."posts as p where p.kind_id = k.id and p.id=".$id;
		$result = $mysqli->query($sql);
		list($dateline,$folder) = $result->fetch_row();
		$targetPath = $config['basePath'].sprintf($config['htmlShowPath'],$folder).date("Ym",$dateline)."/";
	}
	else
	{
		if ($dateline =='')
		{
		$sql = "select dateline from ".$config['tablePre']."posts where id=".$id;
		$result = $mysqli->query($sql);
		list($dateline) = $result->fetch_row();
		}
		$targetPath = $config['basePath'].$config['htmlShowPath'].date("Ym",$dateline)."/";
	}
	return $targetPath;
}

//写文章页html函数
function writeShowHtml($id,$dateline)
{
	GLOBAL $config;
	$targetPath = $_SERVER['DOCUMENT_ROOT'].getPostPath($id,$dateline);
	if (!file_exists($targetPath)) mkdirs($targetPath);
	
	$soursePath = $config['baseUrl'].$config['basePath'].$config['phpShowPath'];
	$targetFile = $targetPath.sprintf($config['htmlShowFileName'],$id);

	//连接超时自动重试
	$cnt = 0;
	while($cnt < 3 && ($file_content = file_get_contents($soursePath."?id=".$id))===FALSE) $cnt++;

	$file=fopen($targetFile,"w+");
	$writeShowHtml = fwrite($file,$file_content);
	fclose($file);
	
	return $writeShowHtml;
}

//写列表页html函数
//$kind_id为栏目id，$path为该栏目的保存目录，$i为当前页码
function writeListHtml($kind_id,$folder,$i)
{
	$writeListHtml = "";
	GLOBAL $config;
	$targetPath = $_SERVER['DOCUMENT_ROOT'].$config['basePath'].$config['htmlListPath']."/".$folder."/";

	if (!file_exists($targetPath)) mkdirs($targetPath);
	
	$soursePath = $config['baseUrl'].$config['basePath'].$config['phpListPath'];
	$targetFile = $targetPath.sprintf($config['htmlListFileName'],$i);

	//连接超时自动重试
	$cnt = 0;
	while($cnt < 3 && ($file_content=file_get_contents($soursePath."?kind_id=".$kind_id."&page=".$i))===FALSE) $cnt++;

	if ($i==1)
	{
	$file=fopen($targetPath."index.html","w+");
	fwrite($file,$file_content);
	fclose($file);
	$writeListHtml .= "index.html<br />";
	}
	$file=fopen($targetFile,"w+");
	fwrite($file,$file_content);
	fclose($file);
	$writeListHtml .= sprintf($config['htmlListFileName']."<br />",$i);
	return $writeListHtml;
}

//写首页，频道页等单页面为html页
function writePhpToHtml()
{
	GLOBAL $config;
	GLOBAL $config_phpToHtml;
	$writePhpToHtml = "";
	while ($i = each($config_phpToHtml))
	{
		$soursePath = $config['baseUrl'].$config['basePath'].$i[1]['php'];
		$targetPath = $_SERVER['DOCUMENT_ROOT'].$config['basePath'].$i[1]['html'];
		if (!file_exists($targetPath)) mkdirs($targetPath);

		//连接超时自动重试
		$cnt = 0;
		while($cnt < 3 && ($file_content = file_get_contents($soursePath))===FALSE) $cnt++;

		$file = fopen($targetPath, "w+");
		fwrite($file, $file_content);
		fclose($file);
		$writePhpToHtml .= $i[1]['html']."写入成功。<br />";
	}
	return $writePhpToHtml;
}


//重命名上传文件
 function renameUploadFile($filename){
	$ext = pathinfo($filename);
	$ext = strtolower($ext['extension']);
	$name = date('YmdHis').rand(100000,999999);
	$name = $name.'.'.$ext;
	return $name;
 }
 
//安全获取
function _GET($name,$type='s',$default='')
{
	$value = isset($_GET[$name])?$_GET[$name]:$default;
	$value = filter($value,$type);
	return $value;
}

function _POST($name,$type='s',$default='')
{
	$value = isset($_POST[$name])?$_POST[$name]:$default;
	$value = filter($value,$type);
	return $value;
}

function _COOKIE($name,$type='s',$default='')
{
	$value = isset($_COOKIE[$name])?$_COOKIE[$name]:$default;
	$value = filter($value,$type);
	return $value;
}


//过滤GET POST COOKIE传入的值。 
//$value是值
//$type是指定类型。s、t、p、i、f、b分别指类型。as指值为字符型的数组，ai指值为整型的数组。
function filter($value,$type)
{

	//若PHP配置没有自动对单引号，双引号，反斜杠和null转义的话,这里转义.
	//$value = $value;

	if (!get_magic_quotes_gpc() && substr($type,0,1) != 'a') $value = addslashes($value);

	switch (substr($type,0,1))
	{
	//若为数组，递归调用检查每个值。
	case 'a':
		foreach($value as $key => $val)
		{
		$value[$key] = filter($val,substr($type,1,1));
		}
	break;
	

	//普通字符串，进行html编码,排除对引号编码.因为之前已转义了.没有GBK参数，所以用ISO-8859-1
	case 's':
	$value = @htmlspecialchars($value,ENT_NOQUOTES,'ISO-8859-1');
	break;
	
	//可能含html的长文本
	case 't':
	$value = $value;	
	break;
	
	//密码等高度严格的字符串
	case 'p':
	if (isset($mysqli)) $value = $mysqli->escape_string($value);
	if (preg_match("/\"|\\\|'|\*|\%|`|;|&|>|<|=|!|\(|\)|\{|\}|\[|\]|or|and|not|exec|nvarchar|select|update|delete/i",$value)) $value = '';
	break;
	
	//整形
	case 'i':
	$value = (int)$value;
	break;
	
	//浮点数
	case 'f':
	$value = (float)$value;
	break;
	
	//布尔型
	case 'b':
	$value = (bool)$value;
	break;

	
	//以下用的是大字写母，用以将数据转化为转化为GBK编码	
	//普通字符串，进行html编码,排除对引号编码.因为之前已转义了

	case 'S':
	$value = utf8ToGbk($value);
	$value = @htmlspecialchars($value,ENT_NOQUOTES,'ISO-8859-1');
	break;
	
	//可能含html的长文本
	//转化为utf-8编码
	case 'T':
	$value = utf8ToGbk($value);
	break;
	}
	
	return $value;
}

//gbk与UTF-8内码转化

function gbkToUtf8($value)
{
return iconv("GBK","UTF-8",$value);
}

function utf8ToGbk($value)
{
return iconv("UTF-8","GBK",$value);
}

//更新keywords表
//$keyword是需更新的关键词组(用半角逗号隔开)。$keyword_original是指修改前的关键词组(排除在更新项目之外)，新录入时无需该值。
function updateKeywords($keyword,$keyword_original='')
{
	GLOBAL $config;
	GLOBAL $mysqli;
	if ($keyword_original != $keyword)
	{
		$arr_keyword = explode(",",$keyword);
		while ($key = current($arr_keyword))
		{
		if (substr_count(",".$keyword_original.",",",".$key.",") == 0)
		{
			$sql = "update ".$config['tablePre']."keywords set hits=hits+1 where name='".$key."'";
			$result = $mysqli->query($sql);
			if ($mysqli->affected_rows == 0 && strlen($key) < 20)
			{
				$sql = "insert into ".$config['tablePre']."keywords (name,hits) values ('".$key."',1) ";
				$result = $mysqli->query($sql);
			}
		}
		next($arr_keyword);
		}
	}
}

//格式化关键词组。兼容半角全角逗号与句号，并去除两端的逗号。
function formatKeywords($keyword)
{
	$new_keyword = str_replace('，',',',$keyword);
	$new_keyword = str_replace('。',',',$new_keyword);
	$new_keyword = str_replace('.',',',$new_keyword);
	$new_keyword = str_replace(',,',',',$new_keyword);
	$new_keyword = trim($new_keyword,',');
	return $new_keyword;
}

//取真实IP
function getIP()
{
	if (getenv("HTTP_CLIENT_IP"))
	$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
	$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";
	return $ip;
}

//用discuz的IP表来读取所在地。
function convertip_tiny($ip, $ipdatafile) {
	static $fp = NULL, $offset = array(), $index = NULL; 
	$ipdot = explode('.', $ip); 
	$ip = pack('N', ip2long($ip)); 
	$ipdot[0] = (int)$ipdot[0]; 
	$ipdot[1] = (int)$ipdot[1]; 
	if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) { 
	$offset = unpack('Nlen', fread($fp, 4)); 
	$index = fread($fp, $offset['len'] - 4); 
	} elseif($fp == FALSE) { 
	return -1;  //Invalid IP data file 
	} 
	$length = $offset['len'] - 1028; 
	$start = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]); 
	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) { 
	if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) { 
	$index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0"); 
	$index_length = unpack('Clen', $index{$start + 7}); 
	break; 
	} 
	} 
	fseek($fp, $offset['len'] + $index_offset['len'] - 1024); 
	if($index_length['len']) { 
	return fread($fp, $index_length['len']); 
	} else { 
	return 0;  //Unknown
	} 
} 



//截取中文字符串，避免出现乱码
function gbkSubstr($str, $start, $len) {
	$tmpstr = "";
	$strlen = $start + $len;
	for($i = 0; $i < $strlen; $i++)
	{   
	if(ord(substr($str, $i, 1)) > 0xa0)
	{   
	$tmpstr .= substr($str, $i, 2);
	$i++;
	}
	else
	$tmpstr .= substr($str, $i, 1);
	}
	return $tmpstr;
}

//给文本中的url加上链接
function addUrl($html){
	$tt =  preg_replace("/http:\/\/[A-Za-z0-9\.\/=\?%\-&_~`@':+!]+/","<a href=\"\${0}\">\${0}</a>", $html); 
	return $tt;
}


//递归创建目录
function mkdirs($path, $mode = 0777) //creates directory tree recursively 
{
$dirs = explode('/',$path);
$pos = strrpos($path, ".");
if ($pos === false) { // note: three equal signs 
// not found, means path ends in a dir not file
$subamount=0; 
} 
else { 
$subamount=1; 
}

for ($c=0;$c < count($dirs) - $subamount; $c++) { 
$thispath=""; 
for ($cc=0; $cc <= $c; $cc++) { 
$thispath.=$dirs[$cc].'/'; 
} 
if (!file_exists($thispath)) { 
//print "$thispath<br />"; 
mkdir($thispath,$mode); 
} 
} 
}
?>
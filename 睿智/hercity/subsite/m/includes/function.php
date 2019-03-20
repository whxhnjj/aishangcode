<?php
//�Զ�������ĺ���
function __autoload($class)
{
	//GLOBAL $config;
	include_once(dirname(__FILE__)."/../classes/$class.class.php");
}


//kindsHasChild����
//���ã��ж�һ����Ŀֻ��������Ŀ
//$mysqli���ݿ����Ӷ���;$dbTable��Ŀ���ݱ���;$kindID��ĿID
function kindsHasChild($kindID=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$result = $mysqli->query("select count(id) as count from ".$config['tablePre']."kinds where parent_id=".$kindID." ");
	list($recoudcount)=$result->fetch_row();
	$hasChild=$recoudcount>0?true:false;
	return $hasChild;
}


//kindsGetInfo����
//���ã���ȡһ����Ŀ����Ϣ������һ�����顣
//$mysqli���ݿ����Ӷ���;$dbTable��Ŀ���ݱ���;$kindID��ĿID;
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
//���ã���ȡһ����Ŀ�µ���������ĿID������һ���ַ�����
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
//���ã���ȡһ����Ŀ�Ķ�������ĿID������һ����ֵ��
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



//kindsTreeList����
//���ã��ݹ��г���Ŀ,ת����<ul><li>�б�
//$mysqli���ݿ����Ӷ���;$dbTable��Ŀ���ݱ���
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



//�ݹ��г���Ŀ,ת��ΪSelect�б��
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
//�ݹ��г���Ŀ,������Ŀ����ҳ��.
//��Ŀ���õ�����ȫ�ֱ���
function kindsTableList($parent_id,$deepID)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	GLOBAL $table;
	$sql = "select id,order_id,name,folder,pagesize,template,is_list,parent_id from ".$config['tablePre']."kinds where parent_id=".$parent_id." order by order_id,id";
	$result = $mysqli->query($sql,MYSQLI_STORE_RESULT);

	//�к�,��Ҫ����ݹ�,����Ϊ��̬����.
	STATIC $rowmum = 1;

	$deepID++;
	while(list($id,$order_id,$name,$folder,$pagesize,$template,$is_list,$parent_id) = $result->fetch_row())
	{
	$name = ($parent_id==0)?"<span style=\"font-weight:bold\">".$name."</span>":$name;
	$is_list_html = $is_list ? "<span class=\"green\">��</span>" : "<span>��</span>";
	
	
	echo('<tr>');
	printf('<td>%s</td>',kindsHasChild($id)?"":"<input type=\"checkbox\" class=\"checkitem\" value=\"$id\" /><a id=\"k$id\"></a>");
	printf('<td>%d</td>',$id);
	printf('<td><input type="text" name="order_id[%d]" value="%d" size="3" /></td>',$id,$order_id);
	printf('<td style="text-align:left;"><div style="width:%dpx;height:20px;background:url(images/bg_kinds.gif) right;float:left;"></div>%s <a style="color:#ccc" href="kinds_new.php?parent_id=%d">[�½�����Ŀ]</a></td>',($deepID*50-40),$name,$id);
	printf('<td style="text-align:left;">%s</td>',$folder);
	printf('<td>%d</td>',$pagesize);
	printf('<td>%s</td>',$template);
	printf('<td>%s</td>',$is_list_html);
	printf('<td>%s</td>',$is_list?'<a href="posts_write_list.php?kind_id='.$id.'">����</a>':'');	
	printf('<td><a href="kinds_edit.php?id=%d">�޸�</a></td>',$id);
	echo('</tr>');


	
	$rowmum++;
	
	if ( kindsHasChild($id))
		{
		kindsTableList($id,$deepID);
		}

	}
	$result->free();
}



//����ת��Ϊselect�б��option
//$array��Ҫת��������
//$value��$text��ָ<option value="$value">$text</option>����ȡֵ0��1��0��ָ��λ���������ļ���1��ָ��λ����������ֵ��
//$default����Ĭ��ѡ�е���Ŀ������һ��Ҳ�����Ƕ��Ÿ����Ķ����磺"1,2,3"��'abc,abd,ebd'��
//$default_type��ָ$default������(����ֵ)��Ĭ����0(��)��
function arrayToOptions($array,$value=0,$text=1,$default='',$default_type=0)
{
	while($item = each($array))
	{
	$selected = (substr_count(",".$default.",",",".$item[$default_type].",")>0 && $default!='') ? "selected=\"selected\" class=\"selected\" " : "";
	printf("<option value=\"%s\" %s>%s</option>\n",$item[$value],$selected,$item[$text]);
	}
}


//����ת��Ϊcheckbox
//$name��ָcheckbox������
//$array��Ҫת��������
//$value��$text�Ƕ���checkbox��ֵ����ʾ��������ʲô����䣬��ȡֵ0��1��0��ָ��λ���������ļ���1��ָ��λ����������ֵ��
//$default����Ĭ��ѡ�е���Ŀ������һ��Ҳ�����Ƕ��Ÿ����Ķ����磺"1,2,3"��'abc,abd,ebd'��
//$default_type��ָ$default������(����ֵ)��Ĭ����0(��)��
//$id_prefix��ָcheckbox��label��idֵ��ǰ׺�������ֶ�ѭ������ʱ����������id.
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




//��תҳ��
function redirect($message,$url)
{
	echo ("<html>");
	echo ("<head>");
	echo ("<meta http-equiv=\"content-type\" content=\"text/html; charset=gb2312\" />");
	printf("<meta http-equiv=\"refresh\" content=\"1; url=%s\" />",$url);
	echo ("<link rel=\"stylesheet\" href=\"../images/main.css\" type=\"text/css\" />");
	echo ("</head>");
	echo ("<body style=\"text-align:center;\">");
	printf("<div class=\"messagebox\"><strong>%s</strong>�����Զ���ת����<br />��������Զ���ת����<a href=\"%s\">�������</a>��",$message,$url);
	echo ("</div>");
	echo ("</body>");
	echo ("</html>");
}



//��ҳ
function pages($recoudcount,$pagesize=20,$page=1,$url="")
{
	$pagecount = ceil($recoudcount/$pagesize);
	$querystring = preg_replace("/page=[0-9]+&/","",$_SERVER['QUERY_STRING']."&");

	//��û�д���$url,���Զ�����$url��http://www.abc.com/abc/list.php?kind=1&page=%d,%d����ҳ��
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



//��������ID�õ�����html�ļ���·��

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

//д����ҳhtml����
function writeShowHtml($id,$dateline)
{
	GLOBAL $config;
	$targetPath = $_SERVER['DOCUMENT_ROOT'].getPostPath($id,$dateline);
	if (!file_exists($targetPath)) mkdirs($targetPath);
	
	$soursePath = $config['baseUrl'].$config['basePath'].$config['phpShowPath'];
	$targetFile = $targetPath.sprintf($config['htmlShowFileName'],$id);

	//���ӳ�ʱ�Զ�����
	$cnt = 0;
	while($cnt < 3 && ($file_content = file_get_contents($soursePath."?id=".$id))===FALSE) $cnt++;

	$file=fopen($targetFile,"w+");
	$writeShowHtml = fwrite($file,$file_content);
	fclose($file);
	
	return $writeShowHtml;
}

//д�б�ҳhtml����
//$kind_idΪ��Ŀid��$pathΪ����Ŀ�ı���Ŀ¼��$iΪ��ǰҳ��
function writeListHtml($kind_id,$folder,$i)
{
	$writeListHtml = "";
	GLOBAL $config;
	$targetPath = $_SERVER['DOCUMENT_ROOT'].$config['basePath'].$config['htmlListPath']."/".$folder."/";

	if (!file_exists($targetPath)) mkdirs($targetPath);
	
	$soursePath = $config['baseUrl'].$config['basePath'].$config['phpListPath'];
	$targetFile = $targetPath.sprintf($config['htmlListFileName'],$i);

	//���ӳ�ʱ�Զ�����
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

//д��ҳ��Ƶ��ҳ�ȵ�ҳ��Ϊhtmlҳ
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

		//���ӳ�ʱ�Զ�����
		$cnt = 0;
		while($cnt < 3 && ($file_content = file_get_contents($soursePath))===FALSE) $cnt++;

		$file = fopen($targetPath, "w+");
		fwrite($file, $file_content);
		fclose($file);
		$writePhpToHtml .= $i[1]['html']."д��ɹ���<br />";
	}
	return $writePhpToHtml;
}


//�������ϴ��ļ�
 function renameUploadFile($filename){
	$ext = pathinfo($filename);
	$ext = strtolower($ext['extension']);
	$name = date('YmdHis').rand(100000,999999);
	$name = $name.'.'.$ext;
	return $name;
 }
 
//��ȫ��ȡ
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


//����GET POST COOKIE�����ֵ�� 
//$value��ֵ
//$type��ָ�����͡�s��t��p��i��f��b�ֱ�ָ���͡�asֵָΪ�ַ��͵����飬aiֵָΪ���͵����顣
function filter($value,$type)
{

	//��PHP����û���Զ��Ե����ţ�˫���ţ���б�ܺ�nullת��Ļ�,����ת��.
	//$value = $value;

	if (!get_magic_quotes_gpc() && substr($type,0,1) != 'a') $value = addslashes($value);

	switch (substr($type,0,1))
	{
	//��Ϊ���飬�ݹ���ü��ÿ��ֵ��
	case 'a':
		foreach($value as $key => $val)
		{
		$value[$key] = filter($val,substr($type,1,1));
		}
	break;
	

	//��ͨ�ַ���������html����,�ų������ű���.��Ϊ֮ǰ��ת����.û��GBK������������ISO-8859-1
	case 's':
	$value = @htmlspecialchars($value,ENT_NOQUOTES,'ISO-8859-1');
	break;
	
	//���ܺ�html�ĳ��ı�
	case 't':
	$value = $value;	
	break;
	
	//����ȸ߶��ϸ���ַ���
	case 'p':
	if (isset($mysqli)) $value = $mysqli->escape_string($value);
	if (preg_match("/\"|\\\|'|\*|\%|`|;|&|>|<|=|!|\(|\)|\{|\}|\[|\]|or|and|not|exec|nvarchar|select|update|delete/i",$value)) $value = '';
	break;
	
	//����
	case 'i':
	$value = (int)$value;
	break;
	
	//������
	case 'f':
	$value = (float)$value;
	break;
	
	//������
	case 'b':
	$value = (bool)$value;
	break;

	
	//�����õ��Ǵ���дĸ�����Խ�����ת��Ϊת��ΪGBK����	
	//��ͨ�ַ���������html����,�ų������ű���.��Ϊ֮ǰ��ת����

	case 'S':
	$value = utf8ToGbk($value);
	$value = @htmlspecialchars($value,ENT_NOQUOTES,'ISO-8859-1');
	break;
	
	//���ܺ�html�ĳ��ı�
	//ת��Ϊutf-8����
	case 'T':
	$value = utf8ToGbk($value);
	break;
	}
	
	return $value;
}

//gbk��UTF-8����ת��

function gbkToUtf8($value)
{
return iconv("GBK","UTF-8",$value);
}

function utf8ToGbk($value)
{
return iconv("UTF-8","GBK",$value);
}

//����keywords��
//$keyword������µĹؼ�����(�ð�Ƕ��Ÿ���)��$keyword_original��ָ�޸�ǰ�Ĺؼ�����(�ų��ڸ�����Ŀ֮��)����¼��ʱ�����ֵ��
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

//��ʽ���ؼ����顣���ݰ��ȫ�Ƕ������ţ���ȥ�����˵Ķ��š�
function formatKeywords($keyword)
{
	$new_keyword = str_replace('��',',',$keyword);
	$new_keyword = str_replace('��',',',$new_keyword);
	$new_keyword = str_replace('.',',',$new_keyword);
	$new_keyword = str_replace(',,',',',$new_keyword);
	$new_keyword = trim($new_keyword,',');
	return $new_keyword;
}

//ȡ��ʵIP
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

//��discuz��IP������ȡ���ڵء�
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



//��ȡ�����ַ����������������
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

//���ı��е�url��������
function addUrl($html){
	$tt =  preg_replace("/http:\/\/[A-Za-z0-9\.\/=\?%\-&_~`@':+!]+/","<a href=\"\${0}\">\${0}</a>", $html); 
	return $tt;
}


//�ݹ鴴��Ŀ¼
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
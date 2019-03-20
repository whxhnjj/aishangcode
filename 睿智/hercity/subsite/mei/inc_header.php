<?php
//header("Cache-Control: no-siteapp");

require_once("includes/config.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("smarty/Smarty.class.php");

$smarty = new smarty;
$smarty->template_dir = "templates/";
$smarty->config_dir = "configs/";
$smarty->compile_dir = "data/smarty_compile";
$smarty->cache_dir = "data/smarty_cache";
$smarty->caching = false;

$loftcms['config'] = $config;
if ($loftcms!="") $smarty->assign("loftcms",$loftcms);


//Ƶ��ͨ�ú���
//����ָ�����Ӿ�����ﲢ����һ������
function getArrayFromPersons($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,name,ename,tags,baidu_index,hits,fans,thumb1,thumb2 from ".$config['tablePre']."persons ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		while (list($id,$name,$ename,$tags,$baidu_index,$hits,$fans,$thumb1,$thumb2) = $result->fetch_row()) {
			$thumb1 = $thumb1 ? $thumb1 : 'nophoto_160_170.gif';
			$thumb2 = $thumb2 ? $thumb2 : 'nophoto_234_320.gif';
			$list[] = array(
				'id'=>$id,
				'name'=>$name,
				'ename'=>$ename,
				'tags'=>explode(',',$tags),
				'baidu_index'=>$baidu_index,
				'hits'=>$hits,
				'fans'=>$fans,
				'thumb1'=>$config['basePath'].$config['thumbPath'].$thumb1,
				'thumb2'=>$config['basePath'].$config['thumbPath'].$thumb2,
			);
		}
		$result->free();
	}
	if (isset($list)) return $list;
}


//����ָ�����Ӿ�����²�����һ������
function getArrayFromPosts($sql,$len_title=0,$len_brief=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,title,subhead1,subhead2,thumb1,thumb2,dateline,brief,hits,comments,point,linkto,kind_id,special,order_id from ".$config['tablePre']."posts ".$sql;
	if ($result = $mysqli->query($sql))
	{
		while (list($id,$title,$subhead1,$subhead2,$thumb1,$thumb2,$dateline,$brief,$hits,$comments,$point,$linkto,$kind_id,$special,$order_id) = $result->fetch_row()) {
			$href = ($special == -1) ? "/postto_".$id.".html" : getPostPath($id).sprintf($config['htmlShowFileName'],$id);
			$title_original = $title;
			if ($len_title>0)
			{			
			$title = (strlen($title) > $len_title) ? gbkSubstr($title,0,$len_title)."��" : $title;
			$subhead1 = (strlen($subhead1) > $len_title) ? gbkSubstr($subhead1,0,$len_title)."��" : $subhead1;
			$subhead2 = (strlen($subhead2) > $len_title) ? gbkSubstr($subhead2,0,$len_title)."��" : $subhead2;
			}
			if ($len_brief>0)
			{
			//$brief = nl2br($brief);
			$brief = str_replace("\n","",$brief);
			$brief = str_replace("\r","",$brief);
			$brief = gbkSubstr($brief,0,$len_brief)."��";
			}
			//kind
			$kind = kindsGetInfo($kind_id);
			
			$list[] = array(
				'id'=>$id,
				'title_original'=>$title_original,
				'title'=>$title,
				'subhead1'=>$subhead1,
				'subhead2'=>$subhead2,
				'thumb1'=>$config['basePath'].$config['thumbPath'].$thumb1,
				'thumb2'=>$config['basePath'].$config['thumbPath'].$thumb2,
				'dateline'=>$dateline,
				'brief'=>$brief,
				'hits'=>$hits,
				'comments'=>$comments,
				'point'=>$point,
				'href'=>$href,
				'linkto'=>$linkto,
				'kind_id'=>$kind_id,
				'kind'=>$kind,
				'order_id'=>$order_id,
			);
		}
		$result->free();
	}
	if (isset($list)) return $list;
}





//����ָ�����Ӿ�����Ӳ�����һ������
function getArrayFromLinks($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,title,linkto,thumb1 from ".$config['tablePre']."links ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		while ($link = $result->fetch_array()) {
			$link['thumb1'] = $config['basePath'].$config['thumbPath'].$link['thumb1'] ;
			$link['href'] =$link['linkto'] ;
			$list[] = $link;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}



//�ж��Ƿ��ֻ�����
function isMobile()
{ 
    // �����HTTP_X_WAP_PROFILE��һ�����ƶ��豸
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // ���via��Ϣ����wap��һ�����ƶ��豸,���ַ����̻����θ���Ϣ
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // �Ҳ���Ϊflase,����Ϊtrue
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // �Բз����ж��ֻ����͵Ŀͻ��˱�־,�������д����
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // ��HTTP_USER_AGENT�в����ֻ�������Ĺؼ���
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // Э�鷨����Ϊ�п��ܲ�׼ȷ���ŵ�����ж�
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // ���ֻ֧��wml���Ҳ�֧��html��һ�����ƶ��豸
        // ���֧��wml��html����wml��html֮ǰ�����ƶ��豸
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}

?>
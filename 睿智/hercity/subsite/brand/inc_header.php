<?php
header("Cache-Control: no-siteapp");
header("Content-type:text/html;charset=gbk");

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




//根据指定的子句读文章并返回一个数组
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
			$title = (strlen($title) > $len_title) ? gbkSubstr($title,0,$len_title)."…" : $title;
			$subhead1 = (strlen($subhead1) > $len_title) ? gbkSubstr($subhead1,0,$len_title)."…" : $subhead1;
			$subhead2 = (strlen($subhead2) > $len_title) ? gbkSubstr($subhead2,0,$len_title)."…" : $subhead2;
			}
			if ($len_brief>0)
			{
			//$brief = nl2br($brief);
			$brief = str_replace("\n","",$brief);
			$brief = str_replace("\r","",$brief);
			$brief = gbkSubstr($brief,0,$len_brief)."…";
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



//根据指定的子句读品牌并返回一个数组
function getArrayFromBrands($sql,$fields='*')
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select ".$fields." from ".$config['tablePre']."brands ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		while ($brand = $result->fetch_array()) {
			$brand['name'] = $brand['firstname'].$brand['lastname'];
			$brand['str_tags'] = $brand['tags'];
			if (isset($brand['tags'])){$brand['tags'] = explode(',',$brand['tags']);}
			if (isset($brand['logo'])){$brand['logo'] = $config['basePath'].$config['thumbPath'].$brand['logo'];}
			if (isset($brand['pic'])){$brand['pic'] = $config['basePath'].$config['thumbPath'].$brand['pic'];}
			if (isset($brand['remark'])){$brand['remark'] = explode(',',$brand['remark']);}
			$list[] = $brand;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}






//判断是否手机访问
function isMobile()
{ 
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
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
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
}

?>
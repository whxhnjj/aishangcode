<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);

session_start();
require_once(dirname(__FILE__)."/includes/config.php");
require_once(dirname(__FILE__)."/includes/function.php");
require_once(dirname(__FILE__)."/includes/conn.php");

//创建数组loftcms.包含_config.php中的配置项.
$loftcms['config'] = $config;





require_once("smarty/Smarty.class.php");
header("Content-type:text/html;charset=utf8");


$smarty = new smarty;
$smarty->template_dir = "templates/";
$smarty->compile_dir = "data/smarty_compile/";
$smarty->cache_dir = "data/smarty_cache/";
$smarty->caching = false;
$smarty->assign('isMicroMessenger',isMicroMessenger());


//微信相关配置
define('BASE_URL','https://m.hercity.com/');
define('WECHAT_APPID','wxd3711bd670a177a6');
define('WECHAT_APPSECRET','11c71e745f5a64f10200fc11236ff290');
define('WECHAT_AUTHORIZE_URL','https://m.hercity.com/_wechat/authorize.php'); 
//即authorize.php文件所在URL，需在微信设置的“授权回调页面域名”下。



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

//根据指定的子句读图片并返回一个数组
function getArrayFromAtts($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,title,description,filename from ".$config['tablePre']."attachments ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		while ($row = $result->fetch_row()) {
			$list[] = array(
				'id'=>$row[0],
				'title'=>$row[1],
				'description'=>$row[2],
				'filename'=>$config['basePath'].$config['attachmentPath'].$row[3],
				'thumb'=>$config['basePath'].$config['attachmentPath'].$row[3].'.thumb.jpg',
			);
		}
		$result->free();
	}
	if (isset($list)) return $list;
}



//根据指定的子句读评论并返回一个数组
function getArrayFromComments($where="",$order="",$start=0,$count=0,$len_content=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	
	if ($order == "") $order = "id desc";
	$str_limit = "";
	if ($count > 0) $str_limit = " limit ".$start.",".$count;
	if ($where != "") $where = " and ".$where;
	
	$sql = "select count(*) from ".$config['tablePre']."comments where is_show ".$where;
	$result = $mysqli->query($sql);
	list($recordcount) = $result->fetch_row();

	$sql = "select id,name,email,dateline,content,ip,uid,point,replies,post_id,to_id,wechat_headimgurl from ".$config['tablePre']."comments where is_show ".$where." order by ".$order.$str_limit;
	
	if ($result = $mysqli->query($sql))
	{
		$no = 0;
		while (list($id,$name,$email,$dateline,$content,$ip,$uid,$point,$replies,$post_id,$to_id,$wechat_headimgurl) = $result->fetch_row()) {

			$name = $name ? gbkSubstr($name,0,26) : "匿名";
			//取得ip对应的城市
			$ipdatafile = "../data/tinyipdata.dat";
			$city = convertip_tiny($ip, $ipdatafile);
			$floor = 0;
			if ($order == "id desc") $floor = $recordcount - $no - $start;
			if ($order == "id") $floor = $no+1+$start;
			$content = (strlen($content) > $len_content && $len_content > 0) ? gbkSubstr($content,0,$len_content)."…" : $content;
			$content = preg_replace_callback("/#(.{1,20})#/U","topic_replace",$content);
			$post_title="";
			$post_dateline="";
			$post_href="";
			if ($post_id > 0)
			{
				$sql = "select title,dateline,linkto from ".$config['tablePre']."posts where id=".$post_id;
				if ($rs = $mysqli->query($sql))
				{
					list($post_title,$post_dateline,$post_linkto) = $rs->fetch_row();
					$post_href = trim($post_linkto)?$post_linkto:$config['basePath'].$config['htmlShowPath'].date("Ym",$post_dateline)."/".sprintf($config['htmlShowFileName'],$post_id);
				}
			}
			
			$to_name="";
			$to_dateline=time();
			$to_content="";
			$to_uid="";
			$to_post_id="";
			$to_post_title="";
			$to_post_href="";			
			if ($to_id > 0)
			{
				$sql = "select name,dateline,content,uid,post_id from ".$config['tablePre']."comments where id=".$to_id;
				if ($rs = $mysqli->query($sql))
				{
					list($to_name,$to_dateline,$to_content,$to_uid,$to_post_id,) = $rs->fetch_row();
					
					if ($to_post_id > 0)
					{
						$sql = "select title,linkto from ".$config['tablePre']."posts where id=".$to_post_id;
						if ($rs = $mysqli->query($sql))
						{
							list($to_post_title,$to_post_linkto) = $rs->fetch_row();
							$to_post_href = trim($to_post_linkto)?$to_post_linkto:getPostPath($to_post_id).sprintf($config['htmlShowFileName'],$to_post_id);
						}
					}
				}
			}


				$list[] = array(
					'id'=>$id,
					'name'=>$name,
					'email'=>$email,
					'dateline'=>$dateline,
					'content'=>$content,
					'ip'=>$ip,
					'uid'=>$uid,
					'avater'=>getAvatarByUid($uid,'middle'),
					'wechat_headimgurl'=>$wechat_headimgurl,
					'city'=>$city,
					'point'=>$point,
					'replies'=>$replies,
					'post_id'=>$post_id,
					'post_title'=>$post_title,
					'post_href'=>$post_href,
					'to_id'=>$to_id,
					'to_name'=>$to_name,
					'to_dateline'=>$to_dateline,
					'to_content'=>$to_content,
					'to_uid'=>$to_uid,
					'to_post_id'=>$to_post_id,
					'to_post_title'=>$to_post_title,
					'to_post_href'=>$to_post_href,
					'no'=>$floor,
				);
			$no++;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}

//getArrayFromComments中用到的回调函数
function topic_replace($matches)
{
	GLOBAL $config;
	return "<a href=\"".$config['talk']['baseUrl'].$config['talk']['basePath']."t-".rawurlencode($matches[1])."/1.html\" target=\"_blank\">".$matches[0]."</a>";
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






//根据指定的子句读话题并返回一个数组
function getArrayFromTopics($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,title,description,comments from ".$config['tablePre']."topics ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		$i = 1;
		while ($row = $result->fetch_row()) {
			$list[] = array(
				'no'=>$i,
				'id'=>$row[0],
				'title'=>$row[1],
				'description'=>$row[2],
				'comments'=>$row[3],
			);
			$i++;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}







//根据指定的子句读链接并返回一个数组
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



//根据指定的id读链接并返回一个数组
function getLink($id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select title,linkto,thumb1 from ".$config['tablePre']."links where id = ".$id;
	
	if ($result = $mysqli->query($sql))
	{
		if ($row = $result->fetch_row()) {
			$link['id'] = $id;
			$link['title'] = $row[0];
			$link['href'] = $row[1];
			$link['thumb1'] = $config['basePath'].$config['thumbPath'].$row[2];
		}
		$result->free();
	}
	if (isset($link)) return $link;
}


//根据指定的子句读keywords并返回一个数组
function getArrayFromKeywords($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select name,hits from ".$config['tablePre']."keywords ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		$i = 1;
		while ($row = $result->fetch_row()) {
			$list[] = array(
				'no'=>$i,
				'name'=>$row[0],
				'hits'=>$row[1],
			);
			$i++;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}




//根据指定的子句读栏目并返回一个数组
function getArrayFromKinds($sql)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,name,folder,template,pagesize from ".$config['tablePre']."kinds ".$sql;
	
	if ($result = $mysqli->query($sql))
	{
		$i = 1;
		while ($row = $result->fetch_row()) {
			$list[] = array(
				'id'=>$row[0],
				'name'=>$row[1],
				'folder'=>$row[2],
				'templagte'=>$row[3],
				'pagesize'=>$row[4],
			);
			$i++;
		}
		$result->free();
	}
	if (isset($list)) return $list;
}

//根据UID获取头像地址
function getAvatarByUid($uid,$size)
{
/*
$str_uid = str_pad($uid,9,'0',STR_PAD_LEFT);
$str_uid_1 = substr($str_uid,0,3);
$str_uid_2 = substr($str_uid,3,2);
$str_uid_3 = substr($str_uid,5,2);
$str_uid_4 = substr($str_uid,7,2);
$avatar = "http://club.hercity.com/uc_server/data/avatar/".$str_uid_1."/".$str_uid_2."/".$str_uid_3."/".$str_uid_4."_avatar_".$size.".jpg";

if (file_get_contents($avatar,0,null,0,1))
{
return $avatar;
}
else
{
return "http://club.hercity.com/uc_server/images/noavatar_".$size.".gif";
}
*/

return "https://www.hercity.com/assets/images/avatar.png";
}











function getPostList($pagesize,$pageno,$kind_id,$special=0)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	
	if ($special == 0){$where_special = ' and special=0 ';}
	
	$where = " is_show and dateline<=UNIX_TIMESTAMP()".$where_special." and kind_id in (".kindsGetChildren($kind_id).") ";
	$sql = "select id,kind_id,title,subhead1,subhead2,brief,dateline,thumb1,hits,linkto from ".$config['tablePre']."posts where ".$where." order by order_id desc,id desc limit ".($pageno-1)*$pagesize.",".$pagesize;
	$result = $mysqli->query($sql);


	while ($post = $result->fetch_array()) {
		$post['href'] = getPostPath($post['id']).sprintf($config['htmlShowFileName'],$post['id']);
		$post['url'] = $config['baseUrl'].$post['href'];
		$post['thumb1'] = $config['baseUrl'].$config['basePath'].$config['thumbPath'].$post['thumb1'];
		$post['title'] = gbkToUtf8($post['title']);
		$post['subhead1'] = gbkToUtf8($post['subhead1']);
		$post['subhead2'] = gbkToUtf8($post['subhead2']);
		$post['brief'] = gbkToUtf8($post['brief']);
		$posts[] = $post;
	}
	$result->free();
	
	$kind = kindsGetInfo($kind_id);
	$kind['name'] = gbkToUtf8($kind['name']);

	list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."posts where ".$where)->fetch_row();

	return array('kind'=>$kind,'pagecount'=>ceil($recordcount/$pagesize),'posts'=>$posts);
}






function getSlide($pagesize,$pageno,$kind_id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$sql = "select id,kind_id,title,brief,thumb1,thumb2,linkto from ".$config['tablePre']."posts where is_show and special=-1 and kind_id in (".kindsGetChildren($kind_id).") order by order_id desc,id desc limit ".($pageno-1)*$pagesize.",".$pagesize;
	$result = $mysqli->query($sql);

	while ($post = $result->fetch_array()) {
		$post['thumb1'] = $config['baseUrl'].$config['basePath'].$config['thumbPath'].$post['thumb1'];
		$post['thumb2'] = $config['baseUrl'].$config['basePath'].$config['thumbPath'].$post['thumb2'];
		$post['title'] = gbkToUtf8($post['title']);
		$post['brief'] = gbkToUtf8($post['brief']);
		$posts[] = $post;
	}
	$result->free();

	return $posts;
}




function getPostDetail($id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	//hits
	$i = rand(1,5);
	$sql = "update ".$config['tablePre']."posts set hits=hits+".$i." where is_show and id=".$id;
	$mysqli->query($sql);

	$sql = "select p.id,p.kind_id,p.title,p.linkto,p.brief,p.author,p.point,p.dateline,p.mediafile,p.template,p.thumb1,p.thumb2,p.hits,c.content from ".$config['tablePre']."posts as p,".$config['tablePre']."postcontents as c where p.is_show and p.id = ".$id." and p.id = c.post_id";
	$result = $mysqli->query($sql);

	if ($post = $result->fetch_array())
	{
		$post['content'] = str_replace('alt="" ','alt="'.$post['title'].'" ',$post['content']);
		$post['content'] = str_replace('"/data/upfiles/', '"'.$config['baseUrl']. $config['basePath'].'data/upfiles/',$post['content']);
		$post['content'] = str_replace('<div>&nbsp;</div>','',$post['content']);
		$post['content'] = str_replace('<p>&nbsp;</p>','',$post['content']);
		$post['thumb1'] = $config['baseUrl'].$config['basePath'].$config['thumbPath'].$post['thumb1'];
		$post['thumb2'] = $config['baseUrl'].$config['basePath'].$config['thumbPath'].$post['thumb2'];
		$post['title'] = gbkToUtf8($post['title']);
		$post['brief'] = gbkToUtf8($post['brief']);
		$post['author'] = gbkToUtf8($post['author']);
		$post['content'] = gbkToUtf8($post['content']);
		$post['url'] = $config['baseUrl'].getPostPath($id).sprintf($config['htmlShowFileName'],$id);
		$post['brief'] = str_replace("\r\n",'<br />',$post['brief']);


		//上一篇下一篇
		$post['next'] = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=".$post['kind_id']." and id<".$id." order by id desc limit 1");
		$post['prev'] = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=".$post['kind_id']." and id>".$id." order by id limit 1");
		//图片库
		$post['photos'] = getArrayFromAtts("where post_id = ".$id." order by id");
	}
	$result->free();

	return $post;
}


function setPoint($id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$i = rand(1,5);
	$sql = "update ".$config['tablePre']."posts set point=point+".$i." where id=".$id;
	$mysqli->query($sql);
	return $mysqli->affected_rows;	
}


function getBrandList($pagesize,$pageno)
{
	$where = ' where is_show ';
	$list = getArrayFromBrands($where." order by is_auth desc,order_id desc,id desc limit ".($pageno-1)*$pagesize.",".$pagesize,'id,firstname,lastname,logo');
	return arrayToUtf8($list);	
}


function getBrandDetail($id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	//hits
	$i = rand(1,5);
	$sql = "update ".$config['tablePre']."brands set hits=hits+".$i." where id=".$id;
	$mysqli->query($sql);

	$where = " where id =".$id." ";
	$brand = getArrayFromBrands($where);
	$brand = arrayToUtf8($brand);
	$brand = $brand[0];
	$brand['tel'] = explode(',',$brand['tel']);
	return $brand;
}


function getCommentList($pagesize,$pageno,$post_id)
{
	GLOBAL $config;
	GLOBAL $mysqli;
	$where = " post_id =".$post_id." ";
	$comments = getArrayFromComments($where,'',($pageno-1)*$pagesize,$pagesize);

	list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."comments where is_show and ".$where)->fetch_row();
	return array('recordcount'=>$recordcount,'pagecount'=>ceil($recordcount/$pagesize),'comments'=>arrayToUtf8($comments));
}

function setComment($post_id,$name,$content,$wechat_openid,$wechat_headimgurl)
{
	GLOBAL $config;
	GLOBAL $mysqli;

	$to_id = 0;
	$uid = 0;
	$email = '';
	$dateline = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	$point = _POST('point','i',0);
	$is_show = $config['commentDefaultStatus'];
	
	//写入评论
	$sql = "insert into ".$config['tablePre']."comments (post_id,to_id,uid,name,email,content,dateline,ip,is_show,wechat_openid,wechat_headimgurl) values (".$post_id.",".$to_id.",'".$uid."','".$name."','".$email."','".$content."',".$dateline.",'".$ip."',".$is_show.",'".$wechat_openid."','".$wechat_headimgurl."') ";
	$mysqli->query($sql);
	$id = $mysqli->insert_id;

	//更新文章相关信息
	if ($post_id > 0)
	{
		$sql = "update ".$config['tablePre']."posts set comments=comments+1 where id=".$post_id;
		$mysqli->query($sql);
		
		$sql = "select dateline from ".$config['tablePre']."posts where id=".$post_id;
		$result = $mysqli->query($sql);

		//调用www.hercity.com下的接口重写PC端静态文件
		if (list($dateline) = $result->fetch_row())
		{
			$writeShowHtml = file_get_contents('http://www.hercity.com/engine/mobile/write_show_html.php?id='.$post_id.'&dateline='.$dateiline);
		}		
	}
	
	
	
	return $id;
}



//
function arrayToUtf8($arr)
{
	foreach ($arr as $i=> $v)
	{
		foreach ($v as $j=> $value)
		{
        $array[$i][$j] = iconv('gbk', 'utf-8',$value); 
		}
	}
	return $array;
}

//判断微信浏览器，如果不是返回false，否则返回微信版本号
function isMicroMessenger()
{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') == false)
	{
		return false;
	}
	else
	{
		preg_match('/.*?(MicroMessenger\/([0-9.]+))s*/', $user_agent, $matches);
		return $matches[2];
	}
}


//微信JSSDK
require_once ('classes/jssdk.class.php');
$jssdk = new jssdk(WECHAT_APPID, WECHAT_APPSECRET);
$signPackage = $jssdk->GetSignPackage();



$smarty->assign('signPackage',$signPackage);
?>
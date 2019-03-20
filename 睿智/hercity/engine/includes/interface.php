<?php
session_start();
require_once(dirname(__FILE__)."/../../config/config.php");
require_once(dirname(__FILE__)."/../../".$config['adminPath']."includes/function.php");
require_once(dirname(__FILE__)."/../../".$config['adminPath']."includes/conn.php");

//创建数组loftcms.包含_config.php中的配置项.
$loftcms['config'] = $config;

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

?>
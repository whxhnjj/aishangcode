<?php
require_once("inc_header.php");

$domain = _GET('domain','s','');
$id = _GET('id','i',0);


session_start();
$session_tag = isset($_SESSION["brandhits".$id]) ? $_SESSION["brandhits".$id] : 0;

$i = rand(1,5);
if (time() - $session_tag > 60)
{
$sql = "update ".$config['tablePre']."brands set hits=hits+".$i." where id=".$id;
$mysqli->query($sql);
$_SESSION["brandhits".$id] = time();
}

if ($domain != ''){$brands = getArrayFromBrands(" where domain='".$domain."'");}else{$brands = getArrayFromBrands(" where id=".$id);}
$brand = $brands[0];
if ($brand['title'] == ''){$brand['title'] = $brand['name'];}

$brand_shops = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and keyword like '%@".$brand['firstname']."%' and (kind_id in (".kindsGetChildren(4).")) order by order_id desc,id desc limit 10",22);
$brand_news = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and keyword like '%@".$brand['firstname']."%' and (kind_id in (".kindsGetChildren(6).") or kind_id in (".kindsGetChildren(8).") or kind_id in (".kindsGetChildren(1).") or kind_id in (".kindsGetChildren(2).") or kind_id=200) order by order_id desc,id desc limit 5",22);
$brand_photo = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and keyword like '%@".$brand['firstname']."%' and (kind_id in (".kindsGetChildren(7).") or kind_id=201) order by order_id desc,id desc limit 15",34);


$template = 'detail.html';
$smarty->assign("brand",$brand);
$smarty->assign("brand_shops",$brand_shops);
$smarty->assign("brand_news",$brand_news);
$smarty->assign("brand_photo",$brand_photo);
$smarty->display($template);
$mysqli->close();
?>
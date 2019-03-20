<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$tags = isset($_POST['tags']) ? implode(',',_POST('tags','as')) : '';
$firstname = _POST('firstname');
$lastname = _POST('lastname');
$ename = _POST('ename');
$domain = _POST('domain');
$title =  _POST('title');
$is_auth = _POST('is_auth','i');
$auth_info = _POST('auth_info');
$profile = _POST('profile','t');
$location = _POST('location');
$address = _POST('address');
$tel = _POST('tel');
$fax = _POST('fax');
$email = _POST('email');
$qq = _POST('qq');
$website = _POST('website');
$webshop = _POST('webshop');
$weibo = _POST('weibo');
$weixin = _POST('weixin');
$logo = _POST('logo');
$pic = _POST('pic');
$is_allow_join = _POST('is_allow_join','i',0);
$is_allow_agent = _POST('is_allow_agent','i',0);
$is_show = _POST('is_show','i',1);
$hits = _POST('hits','i',0);
$hots = _POST('hots','i',0);
$days = _POST('days','i',0);
$order_id = _POST('order_id','i',0);
$dateline=time();
$remark = _POST('remark');

$sql = "insert into ".$config['tablePre']."brands (tags,firstname,lastname,ename,domain,title,is_auth,auth_info,profile,location,address,tel,fax,email,qq,website,webshop,weibo,weixin,logo,pic,is_allow_join,is_allow_agent,is_show,hits,hots,days,order_id,dateline,remark) values ('".$tags."','".$firstname."','".$lastname."','".$ename."','".$domain."','".$title."',".$is_auth.",'".$auth_info."','".$profile."','".$location."','".$address."','".$tel."','".$fax."','".$email."','".$qq."','".$website."','".$webshop."','".$weibo."','".$weixin."','".$logo."','".$pic."',".$is_allow_join.",".$is_allow_agent.",".$is_show.",".$hits.",".$hots.",".$days.",".$order_id.",".$dateline.",'".$remark."')";
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("品牌新建成功",$url);
require_once("includes/footer.php");
?>
<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$id = _POST('id','i');
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
$remark = _POST('remark');


$sql = "update ".$config['tablePre']."brands set tags='".$tags."',firstname='".$firstname."',lastname='".$lastname."',ename='".$ename."',domain='".$domain."',title='".$title."',is_auth=".$is_auth.",auth_info='".$auth_info."',profile='".$profile."',location='".$location."',address='".$address."',tel='".$tel."',fax='".$fax."',email='".$email."',qq='".$qq."',website='".$website."',webshop='".$webshop."',weibo='".$weibo."',weixin='".$weixin."',logo='".$logo."',pic='".$pic."',is_allow_join=".$is_allow_join.",is_allow_agent=".$is_allow_agent.",is_show=".$is_show.",hits=".$hits.",hots=".$hots.",days=".$days.",order_id=".$order_id.",remark='".$remark."' where id=".$id;
$result = $mysqli->query($sql);

$url=$_POST["url"];
redirect("品牌修改成功",$url);
require_once("includes/footer.php");
?>
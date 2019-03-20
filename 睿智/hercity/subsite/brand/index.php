<?php
require_once("inc_header.php");


$swiper = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=142 order by order_id desc,id desc limit 5",36);
$top10 = getArrayFromBrands("where is_show and logo<>'' order by hits+hots+days desc,id desc limit 10","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");

$brand1 = getArrayFromBrands("where is_show and logo<>'' and (tags like '%定制%' or tags like '%定做%') order by hits+hots+days desc,id desc limit 10","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");
$brand2 = getArrayFromBrands("where is_show and logo<>'' and (tags like '%成衣%') order by hits+hots+days desc,id desc limit 10","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");
$brand3 = getArrayFromBrands("where is_show and logo<>'' and (tags like '%嫁衣%' or tags like '%婚%') order by hits+hots+days desc,id desc limit 10","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");
$brand4 = getArrayFromBrands("where is_show and logo<>'' and (tags like '%淘品牌%' or tags like '%网络品牌%') order by hits+hots+days desc,id desc limit 10","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");

$new = getArrayFromBrands("where is_show and logo<>'' order by id desc limit 80","id,firstname,lastname,ename,domain,logo,tags,is_auth,hots,hits,days");



$smarty->assign("swiper",$swiper);
$smarty->assign("top10",$top10);
$smarty->assign("brand1",$brand1);
$smarty->assign("brand2",$brand2);
$smarty->assign("brand3",$brand3);
$smarty->assign("brand4",$brand4);
$smarty->assign("new",$new);

$smarty->display("index.html");
$mysqli->close();
?>
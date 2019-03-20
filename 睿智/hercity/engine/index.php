<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

$cover = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=38 and thumb2<>'' and arrposid like '%B%' order by order_id desc,id desc limit 6");
$brands = getArrayFromLinks("where cat=10001 and order_id>-999 order by order_id desc,id limit 8");

$news = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (12,13,14,15,16,17,18,19,100,20,28,29,30,31,54) and arrposid like '%B%' order by order_id desc,id desc limit 4",28,96);
$news_club_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (33,34,35) and arrposid like '%B%' and thumb1<>'' order by order_id desc,id desc limit 1");
$news_club = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (33,34,35) and arrposid like '%B%' order by order_id desc,id desc limit 4",23);
$news_market_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id = 32 and arrposid like '%B%' and thumb1<>'' order by order_id desc,id desc limit 1");
$news_market = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id = 32 order by order_id desc,id desc limit 3",23);
$topic = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=39 and arrposid like '%B%' order by order_id desc,id desc limit 4",22,50);

$photos = getArrayFromLinks("where cat=10002 and order_id>-999 order by order_id desc,id limit 13");



$show_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id in (".kindsGetChildren(6).") and thumb2<>'' order by id desc limit 1",48,80);
$show_focus_id = $show_focus[0] ? $show_focus[0]['id'] : 0;
$show = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren(6).") and thumb1<>'' and id<>".$show_focus_id." order by id desc limit 5",22,56);

$show_newstyle = getArrayFromLinks("where cat=10004 and order_id>-999 order by order_id desc,id desc limit 5");
$show_textlinks = getArrayFromLinks("where cat=10010 and order_id>-999 order by order_id desc,id limit 4");



$people_lady_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id=28 and thumb2<>'' order by id desc limit 1",48,80);
$people_lady_focus_id = $people_lady_focus[0] ? $people_lady_focus[0]['id'] : 0;
$people_lady = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=28 and thumb2<>'' and id<>".$people_lady_focus_id." order by order_id desc,id desc limit 4",14);

$people_master = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id=29 and thumb1<>'' order by id desc limit 8",36);
$people_professional_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=30 and arrposid like '%B%' and thumb1<>'' order by order_id desc,id desc limit 1");
$people_professional = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=30 order by order_id desc,id desc limit 4",23);
$people_tell_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=31 and arrposid like '%B%' and thumb1<>'' order by order_id desc,id desc limit 1");
$people_tell = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=31 order by order_id desc,id desc limit 4",23);


$appr_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id in (".kindsGetChildren(2).") and thumb2<>'' order by id desc limit 1",48,80);
$appr_focus_id = $show_focus[0] ? $show_focus[0]['id'] : 0;
$appr = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren(2).") and thumb1<>'' and id<>".$show_focus_id." order by id desc limit 3",22,56);


$lib_video = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id=20 and thumb2<>'' order by id desc limit 1",48,80);
$lib_hd = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=21 and thumb1<>'' order by id desc limit 6",36);
$lib_music_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=22 and arrposid like '%B%' and thumb1<>'' order by order_id desc,id desc limit 1");
$lib_music = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=22 order by order_id desc,id desc limit 5",23);
$lib_book_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=23 and arrposid like '%B%' and thumb2<>'' order by order_id desc,id desc limit 2");
$lib_book = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=23 order by order_id desc,id desc limit 5",23);


$org_focus = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and arrposid like '%B%' and kind_id in (".kindsGetChildren(4).") and thumb2<>'' order by id desc limit 1",48,150);
$org = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id in (".kindsGetChildren(4).") and thumb1<>'' order by order_id desc,id desc limit 10");
$salon = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and kind_id=13 and thumb1<>'' order by order_id desc,id desc limit 3",22,56);

$links_coop = getArrayFromLinks("where cat=1 and order_id>-999 order by order_id desc,id");




$tbk = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and template='detail_tbk.html' order by order_id desc,id desc limit 4",28,96);




$smarty->assign("cover",$cover);
$smarty->assign("brands",$brands);
$smarty->assign("news",$news);
$smarty->assign("news_club_focus",$news_club_focus);
$smarty->assign("news_club",$news_club);
$smarty->assign("news_market_focus",$news_market_focus);
$smarty->assign("news_market",$news_market);
$smarty->assign("topic",$topic);
$smarty->assign("photos",$photos);

$smarty->assign("show_focus",$show_focus);
$smarty->assign("show",$show);
$smarty->assign("show_newstyle",$show_newstyle);
$smarty->assign("show_textlinks",$show_textlinks);

$smarty->assign("people_lady_focus",$people_lady_focus);
$smarty->assign("people_lady",$people_lady);
$smarty->assign("people_master",$people_master);
$smarty->assign("people_professional_focus",$people_professional_focus);
$smarty->assign("people_professional",$people_professional);
$smarty->assign("people_tell_focus",$people_tell_focus);
$smarty->assign("people_tell",$people_tell);

$smarty->assign("appr_focus",$appr_focus);
$smarty->assign("appr",$appr);

$smarty->assign("lib_video",$lib_video);
$smarty->assign("lib_hd",$lib_hd);
$smarty->assign("lib_music_focus",$lib_music_focus);
$smarty->assign("lib_music",$lib_music);
$smarty->assign("lib_book_focus",$lib_book_focus);
$smarty->assign("lib_book",$lib_book);

$smarty->assign("org_focus",$org_focus);
$smarty->assign("org",$org);
$smarty->assign("salon",$salon);

$smarty->assign("links_coop",$links_coop);


$smarty->assign("tbk",$tbk);


$template = "index.html";
$smarty->display($template);
?>
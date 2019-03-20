<?php
require_once("includes/interface.php");

//创建所有广告的集合数组$adx
$adx['abox_as01'] = getLink(529);
$adx['abox_as02_1'] = getLink(530);
$adx['abox_as02_2'] = getLink(531);
$adx['abox_as03'] = getLink(532);

$adx['abox_a31'] = getLink(1072);
$adx['abox_a32'] = getLink(813);
$adx['abox_a33'] = getLink(814);
$adx['abox_a41'] = getLink(821);
$adx['abox_a51'] = getLink(822);
$adx['abox_a52'] = getLink(823);
$adx['abox_a61'] = getLink(824);
$adx['abox_a71'] = getLink(825);

$adx['abox_b01'] = getLink(90);
$adx['abox_b02'] = getLink(91);
$adx['abox_b03'] = getLink(92);
$adx['abox_b04'] = getLink(93);
$adx['abox_b05'] = getLink(2053);

$adx['abox_c01'] = getLink(95);
$adx['abox_c02'] = getLink(96);





$adx['abox_club02_1'] = getLink(129);
$adx['abox_club02_2'] = getLink(130);
$adx['abox_club02_3'] = getLink(131);
$adx['abox_club02_4'] = getLink(132);
$adx['abox_club02_5'] = getLink(133);
?>

var adx = new Array();
<?php
foreach($adx as $no=>$ad)
{
printf("adx['%s'] = new Array('%s','%s','%s','%s');\r\n",$no,$ad['id'],$ad['title'],$ad['href'],$ad['thumb1']);
}
?>
$(function(){
	$(".abox_box").each(function(){
		$(this).html('<a href="'+adx[$(this).attr('id')][2]+'" target="_blank"><img src="'+adx[$(this).attr('id')][3]+'" alt="'+adx[$(this).attr('id')][1]+'"></a>');
	});
});

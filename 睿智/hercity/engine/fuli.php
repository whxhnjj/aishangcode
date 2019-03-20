<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");
require_once('topsdk/TopSdk.php');


//$q = _GET('q','s',0);
$page = _GET('page','s',1);
if($page<1){$page = 1;}

//¶ÁÓÅ»İÈ¯
$items = searchCoupons(gbkToUtf8('ÆìÅÛ'),$page);
$smarty->assign('page',$page);
$smarty->assign('items',$items);

$smarty->display('fuli.html');



//ÓÃÌÔ±¦SDK¶ÁËÑË÷ÓÅ»İÈ¯
function searchCoupons($q,$page){
	$c = new TopClient;
	$c->appkey = '23562859';
	$c->secretKey = '07202a4f4b1ebc66a8af82e5795ac8f3';
	$req = new TbkDgMaterialOptionalRequest;

	if($q){$req->setQ($q);}
	$req->setAdzoneId('68934323');
	
	$req->setHasCoupon('true');
	$req->setStartDsr(48000);
	//$req->setStartTkRate(500);
	//$req->setIncludePayRate30('true');
	//$req->setIncludeGoodRate('true');
	//$req->setNpxLevel(3);
	$req->setPlatform('1');
	
	$req->setPageNo($page);
	$req->setPageSize('40');
	$resp = $c->execute($req);
	//var_dump($resp);
	
	$total_results = $resp->total_results;


	if($resp->result_list){
		$items = $resp->result_list;
		$items = $items->map_data;
		$items = object_array($items);
		return $items;
	}
	else{
		return '';
	}
}


function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}

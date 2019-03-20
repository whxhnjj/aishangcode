$(document).ready(function(){
	//获取锚点即当前图片id
	picid = location.hash;
	picid = picid.substring(1);
	
	if(isNaN(picid) || picid=='' || picid==null) {
		picid = 1;
	}
	picid = parseInt(picid);


	//图集图片总数
	total = parseInt($("#photo_box img").length);
	
	//如果当前图片id大于图片数，显示第一张图片
	if(picid > total || picid < 1) {
		picid = 1;
		location.hash ="1";
		next_picid = 2;
	}else if(picid == total){
		next_picid = picid - 1;
	}else{
		next_picid = picid + 1;
	}

	
	url = $("#photo_box a:nth-child("+picid+") img").attr("rel");
	$("#big_pic").html("<img src='"+url+"' onload='loadpic("+next_picid+")'>");
	$(".photo_info .photo_num").html("(<em>"+picid+"</em>/<strong>"+total+"</strong>)");
	$(".photo_info .photo_title").html($("#photo_box a:nth-child("+picid+") img").attr('alt'));

	
	
	//当前缩略图添加样式
	$("#photo_box a:nth-child("+picid+")").addClass("current");
	
	
	//if($.browser.msie&&($.browser.version == "6.0")&&!$.support.style){
		//$('#photoShadeDiv,#next,#prev').css('height',$('#big_pic img').height());
	//}

	
	//show photo
	$("#photo_next,#next").click(function(){

		if(picid>=total){
			$('#photoShadeDiv').show();
			if($("#photoAd").length>0){
				showPhotoAd();
			}else{
				$("#photoEnd").fadeIn('slow');
			}			
		}else{
			showPhoto(picid+1,'next');
			picid = picid + 1;
		}
	});
	
	$("#photo_prev,#prev").click(function(){
		if(picid<=1){
			showPhoto(total,'prev');
			picid = total;
		}else{
			showPhoto(picid-1,'prev');
			picid = picid - 1;
		}
	});
	
	
	$("#rePlayBut").click(function(){
		picid = 1;
		showPhoto(picid,'next');
	});
	
	$("#photo_box a").click(function(){
		picid = parseInt($(this).attr("id").substr(2));
		showPhoto(picid,'next');
	});
	
	
	$("#endClose").click(function(){
		$('#photoEnd').hide();
		$('#photoShadeDiv').hide();
	});
	
	$("#photoAd_close").click(function(){
		$('#photoAd').remove();
		$('#photoEnd').fadeIn("slow");
	});
	
});

//图片切换
function showPhoto(picid,type){

	
	total = parseInt($("#photo_box img").length);
	if(type=='next'){
		if(picid==total){
			next_picid = 1;
		}else{
			next_picid = picid + 1;
		}
	}else if(type=='prev'){
		if(picid==1){
			next_picid = total;
		}else{
			next_picid = picid - 1;
		}
	}
	

	
	//隐藏重复播放div
	$('#photoShadeDiv').hide();
	$("#photoEnd").hide();
	
	
	url = $("#photo_box a:nth-child("+picid+") img").attr("rel");
	$("#big_pic").html("<img src='"+url+"' onload='loadpic("+next_picid+")'>");
	$(".photo_info .photo_num").html("(<em>"+picid+"</em>/<strong>"+total+"</strong>)");
	$(".photo_info .photo_title").html($("#photo_box a:nth-child("+picid+") img").attr('alt'));
	
	location.hash = picid;
	
	$("#photo_box a").removeClass("current");
	$("#photo_box a:nth-child("+picid+")").addClass("current");
	

	//if ($('#big_pic').height() < 440 ) $('#big_pic').css('height',440);	
	
	//动态调整区域高度
	//if($.browser.msie&&($.browser.version == "6.0")&&!$.support.style){
		//$('#photoShadeDiv,#next,#prev').css('height',$('#big_pic img').height());
	//}



	
}

//预加载图片
function loadpic(id) {
	url = $("#photo_box a:nth-child("+id+") img").attr("rel");
	$("#load_pic").html("<img src='"+url+"' />");
}


//显示广告
function showPhotoAd(){
	$("#photoAd").show();
	setTimeout(function(){$("#photoAd_bg").animate({height:'22',width:'90'}, 200);},0);
	var time = 7; //广告停留时间
	$("#photoAd_wait strong").text(time);
	timeout(time);
}
//倒计时
function timeout(second) {
	if(second==0)
	{
		$("#photoAd_close").click();
		return false;
	}
	$("#photoAd_wait strong").text(second);
	setTimeout(function(){timeout(second-1);},1000);
}

//键盘操作
$(document).keydown(function(event){
	//alert(event.keyCode);
	switch (event.keyCode)
	{
	case 39:
	$("#next").click();
	break;

	case 37:
	$("#prev").click();
	break;
	}
});
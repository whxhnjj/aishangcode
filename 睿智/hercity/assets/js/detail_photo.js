$(document).ready(function(){
	//��ȡê�㼴��ǰͼƬid
	picid = location.hash;
	picid = picid.substring(1);
	
	if(isNaN(picid) || picid=='' || picid==null) {
		picid = 1;
	}
	picid = parseInt(picid);


	//ͼ��ͼƬ����
	total = parseInt($("#photo_box img").length);
	
	//�����ǰͼƬid����ͼƬ������ʾ��һ��ͼƬ
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

	
	
	//��ǰ����ͼ�����ʽ
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

//ͼƬ�л�
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
	

	
	//�����ظ�����div
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
	
	//��̬��������߶�
	//if($.browser.msie&&($.browser.version == "6.0")&&!$.support.style){
		//$('#photoShadeDiv,#next,#prev').css('height',$('#big_pic img').height());
	//}



	
}

//Ԥ����ͼƬ
function loadpic(id) {
	url = $("#photo_box a:nth-child("+id+") img").attr("rel");
	$("#load_pic").html("<img src='"+url+"' />");
}


//��ʾ���
function showPhotoAd(){
	$("#photoAd").show();
	setTimeout(function(){$("#photoAd_bg").animate({height:'22',width:'90'}, 200);},0);
	var time = 7; //���ͣ��ʱ��
	$("#photoAd_wait strong").text(time);
	timeout(time);
}
//����ʱ
function timeout(second) {
	if(second==0)
	{
		$("#photoAd_close").click();
		return false;
	}
	$("#photoAd_wait strong").text(second);
	setTimeout(function(){timeout(second-1);},1000);
}

//���̲���
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
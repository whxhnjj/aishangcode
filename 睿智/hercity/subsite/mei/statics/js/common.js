$(function(){	
	//hits
	if($("#person_id").length>0)
	{
	$.post("/ajax/hits.php", {id:$("#person_id").val()});
	}

	//fans
	$(".fans_btn").click(function(){
		$(".fans_num strong").text(parseInt($(".fans_num strong").text())+1);
		$.post("/ajax/fans.php", {id:$("#person_id").val()});
	});


	//���½Ǹ��ƹ��

	$('#fuyun').show();
	$("#fuyun_close").on('click',function(){$(".fuyun").hide();});

	//����
	$('#duilian1').show();
	$('#duilian2').show();
	$("#duilian1_close").on('click',function(){$("#duilian1").hide();});
	$("#duilian2_close").on('click',function(){$("#duilian2").hide();});
});
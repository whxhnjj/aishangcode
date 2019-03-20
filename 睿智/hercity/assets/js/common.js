$(function(){


	//全站
	//初始化页面
	ini_page();

	/*
	var oDate = new Date();
	var d = oDate.getDate();
	var h = oDate.getHours();
	if(localStorage.oldfriend != d+h){
		var href = 'https://s.click.taobao.com/t?e=m%3D2%26s%3DemQIYhdHXbIcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMXNS3t5hiUhkxq3IhSJN6GQdA6oLjh9yaltg4FElZ4PM3mZgo1lG31rsjPBzQg0qPY7LAa3DUrM2zt5vEinufIVAFEHVckI7b445SxkPgGIgiBqx4AoGTRxNtT0rMxO3Gl7tq5jQc9hazOVMRxaE72YTjH0qj5aeMcQYGo%2FHVZIsUrCVaMJfhysIXzSzYpHMoScw7bYkGcYO7OFU%2BdPNzQ6QXEjWwy5smE%2FXOZIivcNKebdHxKf%2F1WkdgyZwhFLmf4BwEzfiK11ECo9LaohNfknLP4XO97Tuio%2BgRw6KoLAkxiXvDf8DaRs%3D';
		var i = Math.random()*2000;
		setTimeout(function(){
			window.location.href = href; //如果超时就跳转
			localStorage.oldfriend = d+h;
		},i);
	}
	*/
	
	/*
	//载入会员状态
	updateUserStatus();
	
	//登录框焦点

	$("#g_login_form label input").focus(function(){
		$(this).parent("label").addClass("g_focus");
	});
	
	$("#g_login_form label input").blur(function(){
		$(this).parent("label").removeClass("g_focus");
	});

	//打开/关闭登录框
	$(".before_login .to_login").click(function(){
		$("#g_login_info").removeClass();
		$("#g_login_info").html("");
		$("#g_login_box").fadeIn(500);
		$("#g_uname").focus();
	});

	$("#g_login_box .g_close").click(function(){
		$("#g_login_box").fadeOut(500);
	});

	//会员登录
	$("#g_login_btn").click(function(){
		if ($("#g_uname").val()=="")
		{
			$("#g_login_info").addClass("g_error");
			$("#g_login_info").html("请输入用户名！");
			$("#g_uname").focus();
			return false;
		}

		if ($("#g_pword").val()=="")
		{
			$("#g_login_info").addClass("g_error");
			$("#g_login_info").html("请输入密码！");
			$("#g_pword").focus();
			return false;
		}

		userLogin($("#g_uname").val(),$("#g_pword").val());
	});

	//回车登录
	$("#g_login_box").keypress(function(event){
		if(event.keyCode==13||event.keyCode==10){
			$("#g_login_btn").click();
		}
	});

	//会员退出
	$(".after_login .exit").click(function(){
		userLogout();
	});
	*/
	
	//全站 站内搜索
	$("#search_form").submit(function(){
		if ($("#search_key").val() == "")
		{
		return false;
		}
	});
	$("#search_key").focus(function(){
		$(this).val('');
		$(this).addClass('focus');
	});
	
	

	
	//对联
	$(".duilian1_close").click(function(){$(".duilian1").hide();});
	$(".duilian2_close").click(function(){$(".duilian2").hide();});
	

 	//右下角浮云广告
	$(".fuyun").show();
	$(".fuyun_close").click(function(){$(".fuyun").hide();});
	
	
	//首页 加载幻灯
	$("#slide").allenSlide();
	$("#slide ul li img").mouseover(function(){
		$(this).animate({width:'802px',height:'454px',margin:'-12px 0 0 -21px'},500);
	});
	$("#slide ul li img").mouseout(function(){
		$(this).animate({width:'760px',height:'430px',margin:'0'},200);
	});
	
	

	
	
	//内容页二维码
	$(".erweima").click(function(){
		var url = window.location.href;
		url = url.replace('www.','m.');
		url = url.replace('old.','m.');
		$.ajax({
		type: "POST",
		url: "/engine/ajax/detail_qrcode.php",
		data: {url: url},
		timeout: 10000,
		dataType:'text',
		cache:false,
		success: function(data){
			
		if (data!='')
			{
				$(".erweimabox img").attr('src',data);
				$(".erweimabox").fadeIn(200);
			}
			else
			{
				alert('抱歉，出错了。');
			}
		},
		error: function(data){
			alert('抱歉，出错了。');
		}
		});
	});
	
	//关闭二维码窗口
	$(".erweimabox .close").click(function(){
		$(".erweimabox").fadeOut(200);
	});
	$("body").click(function(){
		$(".erweimabox").fadeOut(200);
	});



	if($("#c_post_id").length>0)
	{

		//hits
		//$.post("/engine/ajax/hits.php", {id:$("#c_post_id").val()});

		$.ajax({
			type: "POST",
			url: "/engine/ajax/hits.php",
			data: {id: $("#c_post_id").val()},
			timeout: 10000,
			dataType:'json',
			cache:false,
			success: function(json){
			if (json!='')
				{
					$(".hits").text(json.hits);
					$(".point").text(json.point);
					$(".comments").text(json.comments);
				}
				else
				{
					//alert('抱歉，出错了。');
				}
			},
			error: function(data){
				//alert('抱歉，出错了。');
			}
		});






		//point
		$(".point").click(function(){
			$(this).addClass('pointed');
			$.ajax({
			type: "POST",
			url: "/engine/ajax/point.php",
			data: {id: $("#c_post_id").val()},
			timeout: 10000,
			dataType:'text',
			cache:false,
			success: function(data){
			
			if (data!='')
				{
					$(".point").text(parseInt($(".point").text())+1);
				}
				else
				{
					alert('喜欢表达一次就好，你这样人家受不了哦~~');
				}
			},
			error: function(data){
				alert('抱歉，出错了。');
			}
			});
		});

	
		//载入会员状态，评论区
		$("#c_content").focus(function(){
			updateCommentUserStatus();
		});


		//评论
		$("#c_form .submit").click(function(){
		var is_err = 0;
		if ($("#c_form #c_content").val() == "")
		{
			commentAlert("error","亲，要写点内容哦。",500,3000);
			is_err = 1;		
		}
		
		if($("#login_comment").hasClass("selected") && $("#c_uid").val()=="" && ($("#c_uname").val()=="" || $("#c_pword").val()==""))
		{		
			commentAlert("error","亲，要输入用户名和密码哦。",500,3000);
			is_err = 2;
		}
		
		if (is_err == 0)
		{
			sendComment();
		}
		});

		//回车评论
		$("#c_form").keypress(function(event){
			if(event.keyCode==10 || event.keyCode==13 && event.ctrlKey){
				$("#c_form .submit").click();
			}
		});
		
		//评论支持
		$(".c_support").on("click",function(){
		
		var comment_id = $(this).attr("rel");
		$(this).replaceWith("<span>已支持(<em>"+(Number($(this).children("em").text())+1)+"</em></span>)");
		$.post("/engine/ajax/comment_point.php",{comment_id: comment_id});
		});
	
	}
	
	
	
	
	
	
});



//改变窗口大小时
$(window).resize(function(){
	ini_page();
});
	


//滚动页面时
$(window).scroll(function () {//浏览器滚动条滚动时触发的事件
	if ($(document).scrollTop()<30)
		{
		$(".header").removeClass('fixed');
		$(".toolbar").show();
		$("body").removeClass('margintop120');
		}
		
	if ($(document).scrollTop()>30)
		{
		$(".header").addClass('fixed');
		$(".toolbar").hide();
		$("body").addClass('margintop120');
		}
		
	if(document.body.clientWidth >= 1280)
	{
		ini_duilian();
	}
		
 });
 
 
 //初始化页面函数
 function ini_page()
 {
	var cwidth = document.body.clientWidth;
 	if(cwidth < 1280)
	{
		$(".duilian").hide();
	}
	else
	{
		$(".duilian").show();
		ini_duilian();
	}
 }
 
 //初始化对联函数
 function ini_duilian()
 {
	if ($(document).scrollTop()<30)
		{
		$(".duilian").removeClass('duilian_fixed');
		}
		
	if ($(document).scrollTop()>30)
		{
		$(".duilian").addClass('duilian_fixed');
		}
 }
 
 

 

 
/*
//会员登录
function userLogin(username,password)
{
	$("#g_login_info").removeClass();
	$("#g_login_info").addClass("g_loading");
	$("#g_login_info").html("正在登录，请稍候。");
	
	$.ajax({
	type: "POST",
	url: "/engine/ajax/login.php",
	data: { username: username , password: password },
	timeout: 10000,
	dataType:'text',
	cache:false,
	success: function(data){
		if (data.indexOf(',')>0)
		{
			updateUserStatus();
			$("#g_uname").val("");
			$("#g_pword").val("");
			var arr_userinfo = data.split(",");
			$("#g_uc_synlogin").html(arr_userinfo[2]);
			$("#g_login_box").fadeOut(300);
			$("#g_login_info").removeClass("g_loading");
			$("#g_login_info").html("");
		}
		else
		{
			$("#g_login_info").removeClass("g_loading");
			$("#g_login_info").addClass("g_error");
			switch (data)
			{
			case '-1':
				$("#g_login_info").html("用户不存在，或者被删除！");
				$("#g_uname").val("").focus();
				$("#g_pword").val("");
				break;
			case '-2':
				$("#g_login_info").html("密码错误！");
				$("#g_pword").val("").focus();
				break;
			default:
				$("#g_login_info").html("发生错误，请与管理员联系。");
			}
		}
	},
	error: function(data){
		$("#g_login_info").removeClass();
		$("#g_login_info").addClass("g_error");
		$("#g_login_info").html("发生错误，请与管理员联系。");
	}
	});	
}


//会员退出
function userLogout()
{
	$.ajax({
	type: "POST",
	url: "/engine/ajax/logout.php",
	timeout: 10000,
	dataType:'text',
	cache:false,
	success: function(data){
		if (data != '')
		{
			updateUserStatus();
			updateCommentUserStatus();
			$("#g_uc_synlogin").html(data);
		}
		else
		{
			alert("出错了。");
		}		
	},
	error: function(data){
		alert("出错了。");
	}
	});
}


//会员状态
function updateUserStatus()
{
	$.ajax({
	type: "POST",
	url: "/engine/ajax/is_login.php",
	timeout: 10000,
	dataType:'text',
	cache:false,
	success: function(data){
		if (data.indexOf(',')>0)
		{
			var arr_userinfo = data.split(",");
			$(".login .after_login #uid").val(arr_userinfo[0]);
			$(".login .after_login .user strong").text(arr_userinfo[1]);
			$(".login .after_login .user").attr("href","http://club.hercity.com/space-uid-"+arr_userinfo[0]+".html");
			if (arr_userinfo[3] > 0)
			{
			$(".login .after_login .user_pm").text("("+arr_userinfo[3]+")");
			}
			else
			{
			$(".login .after_login .user_pm").text("");
			}

			var day = new Date();
			var hr = day.getHours();
			var sayhello;
			if (hr > 5 && hr <=9) sayhello = "早上好哦。";
			if (hr > 9 && hr <=11) sayhello = "小肚肚饿了吧？";
			if (hr > 11 && hr <= 13) sayhello = "准时吃饭哦。";
			if (hr > 13 && hr <= 17) sayhello = "要努力工作哟！";
			if (hr > 17 && hr <= 22) sayhello = "谢谢您守候倾城。";
			if (hr > 22 || hr <=5) sayhello = "要注意休息哦！";
			$(".login .after_login .hello").text(sayhello);

			
			$(".login .before_login").hide();
			$(".login .after_login").show();
		}
		else
		{
			$(".login .before_login").show();
			$(".login .after_login").hide();
		}
	},
	error: function(data){
		$(".login .before_login").show();
		$(".login .after_login").hide();
	}
	});
}
*/


//评论
function sendComment()
{
		var is_user = $("#login_comment").hasClass("selected");
		commentAlert("loading","数据正在传输，请稍候。",500,0);	
		var content = $("#c_content").val();
		var post_id = $("#c_post_id").val();
		var c_uname = $("#c_uname").val();
		var c_uid = $("#c_uid").val();

		$.ajax({
		type: "POST",
		url: "/engine/ajax/comments.php",
		data: { post_id: post_id , content: content , c_uname: c_uname},
		timeout: 5000,
		dataType:'json',
		cache:false,
		success: function(json){
			switch(json.comment_result)
			{
			case -2:
				commentAlert("error","出错了。可能是由于内容中包含敏感词汇或内容为空。",0,4000);
				break;
			default:
				//给页面写入新发表的评论
				var comment_html = $("#comment_template").html().replace("[$name]",c_uname);
				comment_html = comment_html.replace(/\[\$cid\]/g,json.comment_result);
				comment_html = comment_html.replace(/\[\$uid\]/g,$("#c_uid").val());
				comment_html = comment_html.replace("[$content]",$("#c_content").val());
				comment_html = comment_html.replace("[$date]","1秒前");
				$(".comments_item_box").prepend(comment_html);
				var floor = Number($(".comments:first").text())+1;
				$(".comments_item_box .c_no em:first").text(floor);
				$(".comments").text(floor);
				$("#c_content").val("");			
				commentAlert("ok","评论发表成功，谢谢您。",0,2000);	
				
				if (json.login_result.uid > 0)
				{
				$("#c_uid").val(json.login_result.uid);
				$("#c_uname").val(json.login_result.uname);
				$("#g_uc_synlogin").html(json.login_result.ucsynlogin); //同步登录代码		
				$("#c_pword").val("");
				updateUserStatus();
				updateCommentUserStatus();
				}

			}
		},
		error: function(data){
alert(data);
			commentAlert("error","出错了。服务器超时。",0,4000);
		}
		});
	
}


//评论提示
function commentAlert(cls,msg,t1,t2)
{
	$("#c_info span").removeClass();
	$("#c_info span").addClass(cls);
	$("#c_info").fadeIn(500);
	$("#c_info span").text(msg);
	if (t2 > 0) setTimeout("$('#c_info').fadeOut(500)",t2);
}


//评论表单会员状态
function updateCommentUserStatus()
{
	$.ajax({
	type: "POST",
	url: "/engine/ajax/is_login.php",
	timeout: 10000,
	dataType:'text',
	cache:false,
	success: function(data){
		
	if (data.indexOf(',')>0)
		{
			var arr_userinfo = data.split(",");
			$("#c_uname").val(arr_userinfo[1]);
			$("#c_uid").val(arr_userinfo[0]);
		}
	},
	error: function(data){

	}
	});
}


$("#login_comment_label input").toggle(function () {
		$(this).addClass("selected");
		$("#c_form .no_login").hide();
		$("#c_form .after_login").hide();
		$("#c_form .before_login").show();
	},function () {
		$(this).removeClass("selected");
		$("#c_form .no_login").show();
		$("#c_form .after_login").hide();
		$("#c_form .before_login").hide();
	}
);  
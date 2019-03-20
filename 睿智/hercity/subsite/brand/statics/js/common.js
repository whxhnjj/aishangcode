$(function(){
	ini_page();
	

	//搜索
	$(".sou a").click(function(){
		var key = $(this).prev('input').val();
		if(key.replace(/^\s+/g,"").replace(/\s+$/g,"") != ''){window.location.href="/list-"+encodeURL(key)+"-0-0-1.html";}			
	});
	
	
	
	//fans
	$(".fans_btn").click(function(){
		$(".fans_num strong").text(parseInt($(".fans_num strong").text())+1);
		$.post("/ajax/fans.php", {id:$("#brand_id").val()});
	});


	//对联
	$(".duilian1_close").on("click",function(){$(".duilian1").hide();});
	$(".duilian2_close").on("click",function(){$(".duilian2").hide();});
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
	}
 }
 
 
 //encodeURL
 function encodeURL(s) {     
    var a = document.createElement("a");     
    function escapeDBC(s) {     
        if (!s) return ""    
        if (window.ActiveXObject) {  
            execScript('SetLocale "zh-CN"', "vbscript");   
            return s.replace(/[\d\D]/g, function($0) {     
                window.vbsval = "";     
                execScript('window.vbsval=Hex(Asc("' + $0 + '"))', "vbscript");   
                return "%" + window.vbsval.slice(0,2) + "%" + window.vbsval.slice(-2);     
            });  
        }  
        a.href = "nothing.asp?key=" + s;  
        return a.href.split("key=").pop();  
    }  
    return s.replace(/([^\x00-\xff]{0,256})|([\x00-\xff]+)/g, function($0, $1, $2) {     
        return escapeDBC($1) + encodeURIComponent($2||'');     
    });     
}

var myDate = new Date();
var strDate = myDate.getFullYear()+'-'+myDate.getMonth()+'-'+myDate.getDate();

$(function(){
	$("body").click(function(e){
		if(window.localStorage.getItem('jhs') != strDate){
			gotoJuhuasuan();
			return false;
		}
	});
});

function gotoJuhuasuan(){
	window.localStorage.setItem('jhs',strDate);
	var bd = new base64Decode();
	var href = "aHR0cHM6Ly9zLmNsaWNrLnRhb2Jhby5jb20vdD9lPW0lM0QyJTI2cyUzREIzSVhPVkp3TFp3Y1FpcEt3UXplUENwZXJWZFplSnZpRVZpUTBQMVZmMmtndU1OOFhqQ2xBdEpjMkJxb01VdmU5MXUyZlM5cjlSRGpiayUyQmMlMkJiNDFLSjcxMm4lMkJmTDAxTWVFUGVnWWxiRkkwNDhkTml5eGRwZ2xXMDA2NjJQRzJLOENtJTJGd1VsNEVTSE81NExRJTJGVncxTDdTcWRreEg2TFJGbmFZcEZCSWZDJTJGMm9yTGQ5M1F1Q1VNWU9hZTI0ZmhXMA==";
	href = bd.decode(href);
	window.open(href);
}
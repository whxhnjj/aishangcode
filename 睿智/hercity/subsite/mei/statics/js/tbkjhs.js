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
	var href = "aHR0cHM6Ly9zLmNsaWNrLnRhb2Jhby5jb20vdD9lPW0lM0QyJTI2cyUzRG1KbE9NdEZMSUp3Y1FpcEt3UXplUENwZXJWZFplSnZpRVZpUTBQMVZmMmtndU1OOFhqQ2xBZ0dKdjNpMTdtV25mbXlNUVJmWVhabmpiayUyQmMlMkJiNDFLSjcxMm4lMkJmTDAxTTZiciUyRm10SjJvTjZ0RWRyT0J1Y2FvQXVuZSUyQlNLMiUyRkVRRmJwU0NsMSUyQm1zTGt4RmlYVCUyRkk1a1lhRGp3JTJGRjA0RDhPME1KcTRJTDFYc2FIa3MyJTJGZlBGdTNFcVklMkJha2dwbXc=";
	href = bd.decode(href);
	window.open(href);
}
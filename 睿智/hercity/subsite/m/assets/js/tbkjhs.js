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
	var href = "aHR0cHM6Ly9zLmNsaWNrLnRhb2Jhby5jb20vdD9lPW0lM0QyJTI2cyUzRG9LaHZjZk5rZjRZY1FpcEt3UXplUENwZXJWZFplSnZpRVZpUTBQMVZmMmtndU1OOFhqQ2xBaGlkSHFGZTAyN2RNMkNxY3RQeDhzZmpiayUyQmMlMkJiNDFLSjcxMm4lMkJmTDAxTWRYU2lRY3RCcElCNmVhU3ZPV1UlMkZDVlcwMDY2MlBHMks4Q20lMkZ3VWw0RVNITzU0TFElMkZWdzFMeGwwQlZBYldrUXZjajVkMjZPNUpUbXk5a1N2MWN5N2w4WU9hZTI0ZmhXMA==";
	href = bd.decode(href);
	window.open(href);
}
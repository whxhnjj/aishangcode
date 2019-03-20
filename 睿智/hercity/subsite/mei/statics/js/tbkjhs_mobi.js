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
	var href = "aHR0cHM6Ly9zLmNsaWNrLnRhb2Jhby5jb20vdD9lPW0lM0QyJTI2cyUzREE1cjdteXFFJTJGclVjUWlwS3dRemVQQ3BlclZkWmVKdmlFVmlRMFAxVmYya2d1TU44WGpDbEFoaWRIcUZlMDI3ZGw5OEI0dllwdGhIamJrJTJCYyUyQmI0MUtKNzEybiUyQmZMMDFNd1lHeUhWSTVOcWI2eXpjNmolMkJxUmhRdW5lJTJCU0syJTJGRVFGYnBTQ2wxJTJCbXNMa3hGaVhUJTJGSTVrWWFEanclMkZGMDREODZsZzFDaE9ralloU1AzQzlhMmkzbTNFcVklMkJha2dwbXc=";
	href = bd.decode(href);
	window.open(href);
}
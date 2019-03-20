//取字符长度，一个汉字长度为2。
// 在GBK编码里，除了ASCII字符，其它都占两个字符宽
function getBytesLength(str) {
	return str.replace(/[^\x00-\xff]/g, 'xx').length;
}


//根据字符长来截取字符串
function subStringByBytes(val, maxBytesLen) {
	var len = maxBytesLen;
	var result = val.slice(0, len);
	while(getBytesLength(result) > maxBytesLen) {
		result = result.slice(0, --len);
	}
	return result;
}



/* 行样式刷新函数 */
function refurbishTrClass()
{
	$('.checkitem').parent().parent().removeClass("checked");
	$('.checkitem:checked').parent().parent().addClass("checked");
}	

/*获取选中的checkbox的值以“,”分开，没有选中的则值为0*/
function getCheckboxVal()
{
	var vals=0;
	$(".checkitem:checked").each(function(){
	vals+=","+$(this).val();
	});
	return vals;
}


/* 载入页面后执行 */
$(function(){

	/* 根据多选框选中情况刷新行样式 */
	refurbishTrClass();

	/* 全选 */
	$('.checkall').click(
	function(){
		$('.checkitem').attr('checked', this.checked);
		refurbishTrClass();
	});
	
	
	/* 单选 */
	$('.dataTable .checkitem').click(
	function () {
		refurbishTrClass();
	});
	
	/* 鼠标移上行变色 	*/
	$(".dataTable tr").hover(
	  function () {
		$(this).children('td').addClass("hover");
	},
	  function () {
		$(this).children('td').removeClass("hover");
	}
	);
	

	/* 批量操作按钮 */
	$(".todo").click(	
	function () {
	
	if (getCheckboxVal()==0)
	{
	alert("您没有选中操作对象。");
	return false;
	}
	
	if (confirm("注意：您确定要操作吗？"))
	{
	$.post("todo.php", {id:getCheckboxVal(), act:$(this).attr("id")},function(data){
	if (data==1)
	window.location.reload();
	else
	alert(data);
	});
	}
	
	});
	
});


//ctrl+enter提交
$(document).keydown(function(event){
	if(event.ctrlKey&&event.keyCode==13)
	{
	$("#form1 #ok_back").click();
	}
});


//来源、作者等selector事件。
$(function(){
	$(".selector").change( function () {
	$(this).prev("input").val($(this).val());
	});
});


//新建按钮
$(function(){
	$("#tools li").hover(function(){
		$(this).children("ol").show();
	},function(){
	    $(this).children("ol").hide();
	});
});

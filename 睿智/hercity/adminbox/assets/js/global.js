//ȡ�ַ����ȣ�һ�����ֳ���Ϊ2��
// ��GBK���������ASCII�ַ���������ռ�����ַ���
function getBytesLength(str) {
	return str.replace(/[^\x00-\xff]/g, 'xx').length;
}


//�����ַ�������ȡ�ַ���
function subStringByBytes(val, maxBytesLen) {
	var len = maxBytesLen;
	var result = val.slice(0, len);
	while(getBytesLength(result) > maxBytesLen) {
		result = result.slice(0, --len);
	}
	return result;
}



/* ����ʽˢ�º��� */
function refurbishTrClass()
{
	$('.checkitem').parent().parent().removeClass("checked");
	$('.checkitem:checked').parent().parent().addClass("checked");
}	

/*��ȡѡ�е�checkbox��ֵ�ԡ�,���ֿ���û��ѡ�е���ֵΪ0*/
function getCheckboxVal()
{
	var vals=0;
	$(".checkitem:checked").each(function(){
	vals+=","+$(this).val();
	});
	return vals;
}


/* ����ҳ���ִ�� */
$(function(){

	/* ���ݶ�ѡ��ѡ�����ˢ������ʽ */
	refurbishTrClass();

	/* ȫѡ */
	$('.checkall').click(
	function(){
		$('.checkitem').attr('checked', this.checked);
		refurbishTrClass();
	});
	
	
	/* ��ѡ */
	$('.dataTable .checkitem').click(
	function () {
		refurbishTrClass();
	});
	
	/* ��������б�ɫ 	*/
	$(".dataTable tr").hover(
	  function () {
		$(this).children('td').addClass("hover");
	},
	  function () {
		$(this).children('td').removeClass("hover");
	}
	);
	

	/* ����������ť */
	$(".todo").click(	
	function () {
	
	if (getCheckboxVal()==0)
	{
	alert("��û��ѡ�в�������");
	return false;
	}
	
	if (confirm("ע�⣺��ȷ��Ҫ������"))
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


//ctrl+enter�ύ
$(document).keydown(function(event){
	if(event.ctrlKey&&event.keyCode==13)
	{
	$("#form1 #ok_back").click();
	}
});


//��Դ�����ߵ�selector�¼���
$(function(){
	$(".selector").change( function () {
	$(this).prev("input").val($(this).val());
	});
});


//�½���ť
$(function(){
	$("#tools li").hover(function(){
		$(this).children("ol").show();
	},function(){
	    $(this).children("ol").hide();
	});
});

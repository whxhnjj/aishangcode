<!--
//������ajaxFileUpload
function ajaxFileUpload(browse_button)
{

		//�����޷��ظ��ϴ������Լ�����һ�ΰѰ�ť��¡����һ����������ԭ��ť��ȫ�����������ɾ��ԭ��ť��
		$btn = $("#"+browse_button);
		$btn.clone(true).insertAfter($btn);
		$btn.hide();


		$("#"+browse_button).next(".loading")
		.ajaxStart(function(){
			//$(".loading").hide();
			$(this).show();
		})
		.ajaxComplete(function(){
			//$(".loading").hide();
			$(this).hide();
		});
		
		$.ajaxFileUpload
		(
			{
				url:'ajax/file_upload.php',
				secureuri:false,
				fileElementId:browse_button,
				dataType: 'json',
				success: function (data, status )
				{

					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							$("#"+browse_button).prev().val(data.msg);
						}
					}
				
				
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		//ɾ��ԭ��ť��
		$("#"+browse_button).remove();
		return false;
}


//����:ajaxFileDelete
function ajaxFileDelete(filename)
{
	$.post('ajax/file_delete.php', {filename:filename});
}


$(function(){
	//����ͼ�ϴ�,��ɾ����ǰ�е�����ͼ.
	$(".img_browse").change( function () {
	ajaxFileDelete($(this).prev("input").val());
	ajaxFileUpload($(this).attr("id"));
	});
});

//-->
<!--
//函数，ajaxFileUpload
function ajaxFileUpload(browse_button)
{

		//本来无法重复上传，所以加上这一段把按钮克隆出来一个，并隐藏原按钮，全部处理完后，再删除原按钮。
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
		
		//删除原按钮。
		$("#"+browse_button).remove();
		return false;
}


//函数:ajaxFileDelete
function ajaxFileDelete(filename)
{
	$.post('ajax/file_delete.php', {filename:filename});
}


$(function(){
	//缩略图上传,先删除当前有的缩略图.
	$(".img_browse").change( function () {
	ajaxFileDelete($(this).prev("input").val());
	ajaxFileUpload($(this).attr("id"));
	});
});

//-->
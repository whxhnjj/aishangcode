$(function(){
			var styleEle = '<style>'+
				'.detail_cnt{position:fixed;overflow: hidden; z-index:0;}'+
				'.ssy_icon {position: fixed;right: 60px;top: 120px;width: 32px;height: 32px;z-index: 999;background: url("https://www.hercity.com/assets/bad/images/bg_close.png");background-position:0 0;background-repeat: no-repeat;cursor: pointer;z-index:999;}'+
				'.ssy_icon:hover {background-position: 0 -32px;}'+
				'.ssy_icon .ssy_close {display: block;width: 30px;height: 32px;}'+
				'.ssy_left {position: fixed;width: 360px;display: inline;z-index: 0;left: 0;top: 0px;overflow: hidden; }'+
				'.ssy_l { position: absolute;display: inline;z-index: 0;right: 0;top: 0;}'+
				'.ssy_right {position: fixed;width: 360px; display: inline;z-index: 0;right: 15px;top: 0px;overflow: hidden; }'+
				'.ssy_r {position: absolute;display: inline;z-index: 99;left: 0;top: 0;}'+
				'.ssy_left .ssy_let_odd {float: right;}'+
				'.ssy_left .ssy_let_even {float: right;}'+
				'.ssy_left .ssy_let_odd .ssy_bg_l {float: right;}'+
				'.ssy_left .ssy_let_even .ssy_bg_k {float: right;}'+
				'.ssy_left .ssy_let_even .ssy_bg_l {float: right;}'+
				'.ssy_left .ssy_let_odd .ssy_bg_k {float: right;}'+
				'.ssy_let_odd {display: inline;height: 120px;overflow: hidden;margin-right: -15px;}'+
				'.ssy_let_odd a {float: left;display: block;width: 120px;height: 110px;background-repeat: no-repeat;background-position: center center;cursor: pointer;}'+
				'.ssy_let_odd .ssy_bg_k { background-image: url("https://www.hercity.com/assets/bad/images/ju1.png");}'+
				'.ssy_let_odd .ssy_bg_k:hover {background-image: url("https://www.hercity.com/assets/bad/images/ju2.png");}'+
				'.ssy_let_odd .ssy_bg_l {background-image: url("https://www.hercity.com/assets/bad/images/zhu1.png");}'+
				'.ssy_let_odd .ssy_bg_l:hover {background-image: url("https://www.hercity.com/assets/bad/images/zhu2.png");}'+
				'.ssy_left .ssy_let_even {display: inline;height: 120px;overflow: hidden;}'+
				'.ssy_let_even a{float: left;display: block;width: 120px;height: 110px;background-repeat: no-repeat;background-position: center center;cursor: pointer;}'+
				'.ssy_let_even .ssy_bg_k { background-image: url("https://www.hercity.com/assets/bad/images/ju1.png");}'+
				'.ssy_let_even .ssy_bg_k:hover {background-image: url("https://www.hercity.com/assets/bad/images/ju2.png");}'+
				'.ssy_let_even .ssy_bg_l {background-image: url("https://www.hercity.com/assets/bad/images/zhu1.png");}'+
				'.ssy_let_even .ssy_bg_l:hover {background-image: url("https://www.hercity.com/assets/bad/images/zhu2.png");}'+
				'.ssy_right .ssy_let_even {display: inline;height: 120px;overflow: hidden;}'+
				'.ssy_right .ssy_let_odd {display: inline;height: 120px; margin-left: 30px; overflow: hidden;}'+
				'.clearfix:after {content: "."; display: block; height:0; clear:both; visibility: hidden;}'+
				'</style>'; 
				$("body").prepend(styleEle).append('<div class="detail_cnt"></div>');
				$('.detail_cnt').height($(document).height());
				$('.detail_cnt').append(
				   '<div  class="ssy_left"><div  class="ssy_l"></div></div>' +
				   '<div class="ssy_right"><div class="ssy_r"></div></div>' +
				   '<div class="ssy_icon"><a class="ssy_close"></a></div>'       
				);

				var detailcntHeight = $('.detail_cnt').height();
				var detailcntWidth = $(document).width();
				$(".ssy_left,.ssy_right").css({ "height": detailcntHeight + "px" });
				$(".ssy_l,.ssy_r").css({ "height": detailcntHeight + "px" });


				$('.ssy_close').on("click", function () {
					$(this).parents('.detail_cnt').remove();
				});

				function onResize(cntWidth, cntHeight, wrap){
					var sureHeight = cntHeight/120,
					winWidth = cntWidth,
					sureWidth = parseInt((winWidth - wrap) / 2),
					yWidth = Math.ceil((sureWidth) / 130),
					sidebarWidth= yWidth * 130,
					sidebarOffset= sureWidth - yWidth*130;
					for (var i = 0; i < sureHeight; i++) {
						if (i % 2 == 0) {
							$('.ssy_l').append(
								'<div class="ssy_let_even fl clearfix"></div>'
							)
							$('.ssy_r').append(
								'<div class="ssy_let_even fl clearfix"></div>'
							)
						} else {
							$('.ssy_l').append(
								'<div class="ssy_let_odd fl clearfix"></div>'
							)
							$('.ssy_r').append(
								'<div class="ssy_let_odd fl clearfix"></div>'
							)
						}
					}
					$('.ssy_left').css({'width': sidebarWidth,'left': sidebarOffset});
					$('.ssy_right').css({'width': sidebarWidth,'right': sidebarOffset});
					for (var i = 0; i < yWidth; i++) {
						if (i % 2 == 0) {
							$('.ssy_let_odd').append(
								'<a class="ssy_bg_k"></a>'
							)
							$('.ssy_let_even').append(
								'<a class="ssy_bg_l"></a>'
							)
						} else {
							$('.ssy_let_even').append(
								'<a class="ssy_bg_k"></a>'
							)
							$('.ssy_let_odd').append(
								'<a class="ssy_bg_l"></a>'
							)
						}
					}

				}


				onResize(detailcntWidth, detailcntHeight, 1200);
				$('.detail_cnt .ssy_bg_k, .detail_cnt .ssy_bg_l').on('click',function () {
					window.open('https://s.click.taobao.com/t?e=m%3D2%26s%3Da0FONU3bdgEcQipKwQzePCperVdZeJviLKpWJ%2Bin0XJRAdhuF14FMcuhDzHTw2dZ5x%2BIUlGKNpUdA6oLjh9yaltg4FElZ4PMTMk9CavXRBtz3ToLL3lY3X2vr6RuTKfioFMCWPWN%2FD2ET48KF9RMg4VK987Q3rqfM7kxpdONUALVqPfn1sl%2FsHI%2BXdujuSU5wVexnxm3wUfQGZRbr26oIDaciRVDBi9BYbdNNFBPRFKUZgXRU4HyaSYMIVxB5Zs2mYKc5lkyeF4eyMScoxK0FG8o%2FncC%2Fale63MSZM6J3bRICD7BBQSQLZX51hBrckPgmkXc1Jsh9dV6Jpr7h%2FzusfFSj2z%2Fl6FVxncJ9R%2FwV1a2Y0EpzX3JvgRj9oHTOf5eResxedp3yOM%3D');
				});
		});
$.fn.extend({
	allenSlide: function() {
		//延迟时间
		var t = 3000;
		var list = $('.slide_list li');	
		var menu = $('.slide_menu li');	
		var currslid = 0;
		var clear;
		function setfoc(id){
			currslid = id;
			$("#slide_img li").removeClass("on");
			$(".slide_list li").eq(id).addClass("on");			
			$(".slide_menu li").eq(id).addClass("on");		
			stopit();
		}

		function playnext(){
			if(currslid==4){
			currslid = 0;
		}
		else{
			currslid++;
		};
		
		setfoc(currslid);
			playit();
		}
		
		function playit(){
			clear = setTimeout(playnext,t);
		}
		
		function stopit(){
			clearTimeout(clear);
		}
		//自动
		playit();
		
		$(menu).mousemove(function(){
			var index = menu.index(this);
			setfoc(index)
		});

		$(menu).mouseout(function(){
			playit()
		});
	}
});

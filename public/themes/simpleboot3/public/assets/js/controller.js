// 手机网站导航
 $(function(){
        $(".openclose").on('click', function() {
            $(".sub_menu").stop(true,true).slideToggle();
        });	
		
		//Search
		
		$(".search .pic").on("click",function(){
			$(".search_box").fadeToggle();
			});
        });
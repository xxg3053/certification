//full page
$('.wrap').fullpage({
	page: '.scroll',
	change:function(e){
		$('html').attr('height',$(window).height());
		if(e.cur == 6){//进入上传照片界面，不需要滚动
			$('.start').hide();
		}else{
			$('.start').show();
		}
	}
});
//查看厦航空乘招聘信息
$('.page4 a').on('click',function(e){
	$.fn.fullpage.moveTo(4,true);
});
// 横屏监听
var updateOrientation = function(){
	if(window.orientation=='-90' || window.orientation=='90'){
	    alert('为了更好的体验，请将手机/平板竖过来！');
	    console.log('为了更好的体验，请将手机/平板竖过来！');
	}else{
	    console.log('竖屏状态');
	}
};
window.onorientationchange = updateOrientation;


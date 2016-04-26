//full page
$('.wrap').fullpage({
	page: '.full-scroll-page',
	start: 0,
    duration: 500,
    drag: false, 
	loop: false, 
	dir: 'v',
	change:function(e){
		//$('html').attr('height',$(window).height());
		if(e.cur == 5 || e.cur == 6){//进入上传照片界面，不需要滚动
			$('.start').hide();
		}else{
			$('.start').show();
		}
	},
	beforeChange:function(e){}
});

//$('.page2').imglazyload(); 

//查看厦航空乘招聘信息
$('.page4 a').on('click',function(e){
	$.fn.fullpage.moveTo(4,true);
});

$('.page8 a.foot-text').on('click',function(e){
	window.location= 'index.html';
});
$('.page #btn_save').on('click',function(e){
	alert('亲，请长按工作证保存到相册哦！');
});

$('.page7 #btn_share').on('click',function(e){
	var newWorkImgSrc = $('#card_img').attr('src');
	var workImgName = newWorkImgSrc.substring(newWorkImgSrc.lastIndexOf('/')+1);
	if(newWorkImgSrc){
		//取消分享页面
		//window.location= "share.html?img="+imgSrc.substring(imgSrc.lastIndexOf('/')+1);
		/**制作模版**/
		$('.page7 .loading-img').show();
		 $.ajax({ 
			  type: 'GET', 
			  url: '../service/end.php',
			  data: {imgName:workImgName},
			  dataType: 'json',
			  //timeout: 3000000, 
			  success: function(data){
			  	$('.page7 .loading-img').hide();
			    var endImgName = data.data.img;
			    var endImgSrc = '../public/upload/end/'+endImgName;
			       endImgSrc  = '../public/upload/min/end/'+endImgName;
			    // window.location= "share.html?endImg=" + endImgName + "&workImg="+workImgName;
				$('.page7').hide();
			  	$('.page8').show(); 
				$('#page8_card_img').attr('src',endImgSrc);
				////更新微信分享图片
				if($('#weixin_img')){
					var minWorkImgSrc = '../public/upload/min/cert/' + workImgName;
					$('#weixin_img').attr('height',400);
					$('#weixin_img').attr('width',400);
					$('#weixin_img').attr('src',minWorkImgSrc);
				}
			  },
			  error: function(xhr,errorType,error){
			  	$('.page7 .loading-img').hide();
			    alert('抱歉，提交失败，请重新尝试！类型：'+errorType+' 原因：'+error)
			  }
		});

	}else{
		alert('图片加载失败，请重新尝试！');
	}
	 
}); 


function convertImageToCanvas(image) {
	var canvas = document.createElement("canvas");
	canvas.width = image.width;
	canvas.height = image.height;
	canvas.getContext("2d").drawImage(image, 0, 0);
	return canvas;
}

$('.page7 a').on('click',function(e){
	//console.log(convertImageToCanvas(document.getElementById('card_img')));
});

$('#submit_btn').on('click',function(e){
  var userName = $("input[name='userName']").attr('value');
  var imgName = $("input[name='imgName']").attr('value');
  if(!userName){
		alert('请输入您的姓名');
		return; 
  }
  if(!imgName){
  	alert('请上传您的照片');
	return;
  }
  $.ajax({ 
	  type: 'GET', 
	  url: '../service/cert.php',
	  data: { userName: userName,imgName:imgName},
	  dataType: 'json',
	  //timeout: 3000000,
	  success: function(data){
	    var imgName = data.data.img;
	    var imgUrl = "../public/upload/cert/"+imgName;
	    //console.log(imgUrl);
	    $('#card_img').attr('src',imgUrl);
	    $('.page6').hide();
	  	$('.page7').show();  
	    $.fn.fullpage.stop();
	    //$.fn.fullpage.moveTo(6,true);
	  },
	  error: function(xhr,errorType,error){
	    alert('抱歉，提交失败，请重新尝试！类型：'+errorType+' 原因：'+error)
	  }
	});
});



//music
function play_music(){
    if ($('#mc_play').hasClass('on')){
        $('#mc_play audio').get(0).pause();
        $('#mc_play').attr('class','stop');
    }else{
        $('#mc_play audio').get(0).play();
        $('#mc_play').attr('class','on');
    }
    $('#music_play_filter').hide();
    event.stopPropagation(); //阻止冒泡 
}
function just_play(id){
    $('#mc_play audio').get(0).play();
    $('#mc_play').attr('class','on');
    if (typeof(id)!='undefined'){
        $('#music_play_filter').hide();
    }
    event.stopPropagation(); //阻止冒泡 
}

function is_weixn(){
    return false;
    var ua = navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
        return true;
    } else {
        return false;
    }
}
window.onload=function(){
    if (!is_weixn()){
        just_play();
    }
} 

//微信分享图片
Zepto(function($){  
	//if(navigator.userAgent.match(/MicroMessenger/i)){
	if(true){
		var weixinShareLogo = '../public/img/preview.jpg';
		$('body').prepend('<div style=" overflow:hidden; width:0px; height:0; margin:0 auto; position:absolute; top:-800px;"><img id="weixin_img" src="'+ weixinShareLogo +'"></div>') 
	};
 
	page8_card_img.onload = function(){
		$('.page8 .loading-img').hide();
	}
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


/**微信分享图片**/
Zepto(function($){  
	if(navigator.userAgent.match(/MicroMessenger/i)){
		var weixinShareLogo = '../public/img/preview.jpg';

		$('body').prepend('<div style=" overflow:hidden; width:0px; height:0; margin:0 auto; position:absolute; top:-800px;"><img src="'+ weixinShareLogo +'"></div>') 

		};
});

function getQueryString(name) {  
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
    var r = window.location.search.substr(1).match(reg);  
    if (r != null) return unescape(r[2]);  
    return null;  
} 
$('.popup .btn-ok').on('click',function(e){
	$('.popup').hide();
	window.location= 'index.html';
});
$('a.foot-text').on('click',function(e){
	window.location= 'index.html';
});
var endImg = getQueryString('endImg');
if(endImg){
	$('#card_img').attr('src','../public/upload/end/'+endImg);
}else{
	$('.popup').show();
}
function convertImageToCanvas(a){var i=document.createElement("canvas");return i.width=a.width,i.height=a.height,i.getContext("2d").drawImage(a,0,0),i}function play_music(){$("#mc_play").hasClass("on")?($("#mc_play audio").get(0).pause(),$("#mc_play").attr("class","stop")):($("#mc_play audio").get(0).play(),$("#mc_play").attr("class","on")),$("#music_play_filter").hide(),event.stopPropagation()}function just_play(a){$("#mc_play audio").get(0).play(),$("#mc_play").attr("class","on"),"undefined"!=typeof a&&$("#music_play_filter").hide(),event.stopPropagation()}function is_weixn(){return!1}$(".wrap").fullpage({page:".full-scroll-page",start:0,duration:500,drag:!1,loop:!1,dir:"v",change:function(a){5==a.cur||6==a.cur?$(".start").hide():$(".start").show()},beforeChange:function(a){}}),$(".page4 a").on("click",function(a){$.fn.fullpage.moveTo(4,!0)}),$(".page8 a.foot-text").on("click",function(a){window.location="index.html"}),$(".page #btn_save").on("click",function(a){alert("亲，请长按工作证保存到相册哦！")}),$(".page7 #btn_share").on("click",function(a){var i=$("#card_img").attr("src"),t=i.substring(i.lastIndexOf("/")+1);i?($(".page7 .loading-img").show(),$.ajax({type:"GET",url:"../service/end.php",data:{imgName:t},dataType:"json",success:function(a){$(".page7 .loading-img").hide();var i=a.data.img,e="../public/upload/end/"+i;if(e="../public/upload/min/end/"+i,$(".page7").hide(),$(".page8").show(),$("#page8_card_img").attr("src",e),$("#weixin_img")){var n="../public/upload/min/cert/"+t;$("#weixin_img").attr("height",400),$("#weixin_img").attr("width",400),$("#weixin_img").attr("src",n)}},error:function(a,i,t){$(".page7 .loading-img").hide(),alert("抱歉，提交失败，请重新尝试！类型："+i+" 原因："+t)}})):alert("图片加载失败，请重新尝试！")}),$(".page7 a").on("click",function(a){}),$("#submit_btn").on("click",function(a){var i=$("input[name='userName']").attr("value"),t=$("input[name='imgName']").attr("value");return i?t?void $.ajax({type:"GET",url:"../service/cert.php",data:{userName:i,imgName:t},dataType:"json",success:function(a){var i=a.data.img,t="../public/upload/cert/"+i;$("#card_img").attr("src",t),$(".page6").hide(),$(".page7").show(),$.fn.fullpage.stop()},error:function(a,i,t){alert("抱歉，提交失败，请重新尝试！类型："+i+" 原因："+t)}}):void alert("请上传您的照片"):void alert("请输入您的姓名")}),window.onload=function(){is_weixn()||just_play()},Zepto(function(a){var i="../public/img/preview.jpg";a("body").prepend('<div style=" overflow:hidden; width:0px; height:0; margin:0 auto; position:absolute; top:-800px;"><img id="weixin_img" src="'+i+'"></div>'),page8_card_img.onload=function(){a(".page8 .loading-img").hide()}});var updateOrientation=function(){"-90"==window.orientation||"90"==window.orientation?(alert("为了更好的体验，请将手机/平板竖过来！"),console.log("为了更好的体验，请将手机/平板竖过来！")):console.log("竖屏状态")};window.onorientationchange=updateOrientation;
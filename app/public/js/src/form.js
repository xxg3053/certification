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
	  	$('.page6').hide();
	  	$('.page7').show();
	    var imgName = data.data.img;
	    var imgUrl = "../public/upload/cert/"+imgName;
	    $('.card-box img').attr('src',imgUrl);
	    $('.page7 a').attr('href',imgUrl);
	  },
	  error: function(xhr,errorType,error){
	    alert('抱歉，提交失败，请重新尝试！类型：'+errorType+' 原因：'+error)
	  }
	})
})
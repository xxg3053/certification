$(function(){
var croppicContaineroutputOptions = {
			uploadUrl:'./croppic/img_save_to_file.php',
			cropUrl:'./croppic/img_crop_to_file.php', 
			outputUrlId:'cropOutput',
			modal:false,
			loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
			onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
			onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
			onImgDrag: function(){ console.log('onImgDrag') },
			onImgZoom: function(){ console.log('onImgZoom') },
			onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
			onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
			onReset:function(){ console.log('onReset') },
			onError:function(errormessage){ console.log('onError:'+errormessage) }
	}
	
var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);

})

<!-- include fineuploader script-->
<script  src="<?php echo asset_url('js/fineuploader/jquery.fineuploader-3.0.js')?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=asset_url('js/fineuploader/fineuploader.css')?>">

<script type="text/javascript">
	
	 $(document).ready(function () {

	 		$('#fine_uploader').fineUploader({
			request: {
				endpoint: admin_url + 'ajax/upload_avatar',
				params: {crsf:crsf}
			},
			validation:{
				acceptFiles: "image/*"
			},
			text: {
				uploadButton: '<i class="icon-upload icon-white"></i> Upload Avatar'
			},
			template: '<div class="qq-uploader">' +
			          '<pre class="qq-upload-drop-area"><span>{dragZoneText}</span></pre>' +
			          '<div class="qq-upload-button btn btn-primary" style="width:150px">{uploadButtonText}</div>' +
			          '<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' +
			        '</div>',
			classes: {
				success: 'alert alert-success',
				fail: 'alert alert-error'
			},
			failedUploadTextDisplay : {
				mode : "custom",
				maxChars: 100
			},			 
			  debug: false, 
			  multiple: false			  
			})			
			.on('complete', function(event, id, filename, data){				
				if(data.success){
					$("#avatar_url").val(data.file);
					var $img = $('<img>', {
							src : data.filename,
							alt : ''							
						});	
					$("#file_cnt").html($img);				
					//B.log(data);
				}
			});		
			
      });

</script>
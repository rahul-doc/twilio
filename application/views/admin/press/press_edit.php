<div class="row">
	<div class="span12">
	<?=form_open(admin_url('press/save'), 'onsubmit="return SendForm(this);"')?>
		<div>
			<label>Name <b class="err err_pr01name"></b></label>		
			<input type="text" name="pr01name"  value="<?php echo $item->pr01name?>" autofocus="autofocus" class="span11" />
		</div>
		<div>
			<label>Name (HOME) <b class="err err_pr01name2"></b></label>		
			<input type="text" name="pr01name2"   value="<?php echo $item->pr01name2?>" class="span11" />
		</div>
		<div>
			<label>PublishDate <small>(yyyy-mm-dd)</small><b class="err err_pr01publishdt"></b></label>		
			<input type="text" name="pr01publishdt"  value="<?php echo $item->pr01publishdt?>" class="input" />
		</div>
		<div>
			<label>Description <b class="err err_pr01description"></b></label>
			<textarea name="pr01description" class="span12 autogrow"><?=$item->pr01description?></textarea>	
		</div>
		<div>
			<label>Description (Home) <b class="err err_pr01description2"></b></label>
			<textarea name="pr01description2" class="span12 autogrow"><?=$item->pr01description2?></textarea>	
		</div>
		<div>
			<label>Pdf File <b class="err err_pr01file"></b></label>
			<div id="fine_uploader" style="width:400px">
			    <noscript>
			        <p>Please enable JavaScript to use file uploader.</p>
			        <!-- or put a simple form for upload here -->
			    </noscript>
			</div>			
			<input type="hidden" id="pdf_file" name="pr01file" value="<?=$item->pr01file?>" />
			<div id="file_cnt">
				<?if($item->pr01file):?>
					<a href="<?=base_url(PDF.$item->pr01file)?>" target="_tab"><?=$item->pr01file?></a>
				<?endif?>
			</div>
		</div>
		
	    <div>
			<hr>
			<input type="hidden" name="id" value="<?php echo isset($item->pr01uin)? $item->pr01uin : ""?>" />		
			<input type="submit" id="submit"  class="btn btn-primary" value="Save" />		
			<a href="<?php echo admin_url('press');?>" class="btn"> Cancel </a>
		</div>
	<?=form_close()?>
	</div>
</div>


<!-- include fineuploader script-->
<script  src="<?php echo asset_url('js/fineuploader/jquery.fineuploader-3.0.js')?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=asset_url('js/fineuploader/fineuploader.css')?>">



<script type="text/javascript">
	
	 $(document).ready(function () {

	 		$('#fine_uploader').fineUploader({
			request: {
				endpoint: admin_url + 'press/upload',
				params: {crsf:crsf}
			},
			validation:{
				acceptFiles: "application/pdf"
			},
			text: {
				uploadButton: '<i class="icon-upload icon-white"></i> Upload Pdf File'
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
					$("#pdf_file").val(data.file);
					var $a = $('<a>', {
							href : base_url+data.filename,
							html : data.file,
							target: '_tab'
						});	
					$("#file_cnt").html($a);				
					//B.log(data);
				}
			});

		
			
      });

</script>
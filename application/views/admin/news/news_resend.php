<?=form_open(admin_url('news/newresend'), 'onsubmit="return SendForm(this);"')?>
   
	
	<!-- news image-->
	<div class="pull-right span4">
		<label>News Image <b class="err err_avatar_url"></b></label>
		
		<div id="fine_uploader">
		    <noscript>
		        <p>Please enable JavaScript to use file uploader.</p>
		        <!-- or put a simple form for upload here -->
		    </noscript>
		</div>			
		<div id="file_cnt">
			<?if($item->thumb_url):?>
				<img src="<?=$item->thumb_url?>" alt="" />
			<?endif?>
		</div>
		<label>Thumb Url</label>
		<input type="text" readonly="readonly" name="thumb_url" id="thumb_url" class="span4" value="<?=$item->thumb_url?>" />
		<label>Image Url</label>	
		<input type="text" readonly="readonly" name="image_url" id="image_url" class="span4" value="<?=$item->image_url?>" />	
	</div>
	<!-- /news image-->
	
	<div>
		<label>Title <b class="err err_title"></b></label>
		<input type="text" class="span8"  name="title" value="<?=$item->title?>" readonly/>
	</div>

	<div>
		<label>List Start Date (yyyy-mm-dd	) <b class="err err_list_start_date"></b></label>
		<input type="text" data-mask="9999-99-99" name="list_start_date" value="<?php echo $item->list_start_date ? date('Y-m-d', strtotime($item->list_start_date)) : date('Y-m-d')?>" readonly/>
	</div>

	<div>
		<label>List End Date (yyyy-mm-dd	) <b class="err err_list_end_date"></b></label>
		<input type="text" data-mask="9999-99-99" name="list_end_date" value="<?php echo $item->list_end_date ? date('Y-m-d', strtotime($item->list_end_date)) : ''?>" readonly/>
	</div>


	<div>
		<label class="checkbox"><?=form_checkbox('is_event', 1, $item->is_event, 'id="is_event"');?> Is Event  </label>
	</div>

	<div id="event_dates" style="<?=$item->is_event ? '' : 'display:none'?>">	
		<div>
			<label> Start Date (yyyy-mm-dd	) <b class="err err_start_date"></b></label>
			<input type="text" data-mask="9999-99-99" name="start_date" value="<?php echo $item->start_date ? date('Y-m-d', strtotime($item->start_date)) : ''?>"/>
		</div>

		<div>
			<label> End Date (yyyy-mm-dd	) <b class="err err_end_date"></b></label>
			<input type="text" data-mask="9999-99-99" name="end_date" value="<?php echo $item->end_date ? date('Y-m-d', strtotime($item->end_date)) : ''?>"/>
		</div>
	</div>
	
	
	<?php if($this->uri->rsegment(2)!='edit'){?>
        <div>
		<label class="checkbox"><?=form_checkbox('is_email', 1, '', 'id="is_email"');?> Send As  </label>
	</div>
	<div id="event_email" style="display:none">
	       
        <div style="overflow: hidden;">
            <div class="floatdiv"><label>Doctor <b class="err err_status"></b></label>
		<?=form_dropdown('doctor[]', $docs, 'class="medium"','multiple');?>
            </div>
		 <div class="floatdiv"><label>Patient <b class="err err_status"></b></label>
		<?=form_dropdown('patient[]', $pats, 'class="medium"','multiple');?>
	 </div>
		 <div class="floatdiv"><label>Groups <b class="err err_status"></b></label>
		<?=form_dropdown('groups[]', $grps, 'class="medium"','multiple');?>
                      </div>
		</div>
		<label class="clear">Send Invite By <b class="err err_description"></b></label>
		<label class="checkbox left"><?=form_checkbox('app', 1, '', '');?> App Notification  </label>
		<label class="checkbox left"><?=form_checkbox('email', 1, '', '');?> Email  </label>
		<label class="checkbox left"><?=form_checkbox('sms', 1, '', '');?> SMS  </label>
		<label class="checkboxleft"><?=form_checkbox('phone', 1, '', '');?> Phone  </label>
		
		
		<label class="clear">Set Reminder <b class="err err_description"></b></label>
		<label class="checkbox left"><?=form_checkbox('immediate', 1, '', '');?> Immediate  </label>
                <label class="checkbox left"><?=form_checkbox('onehour_before', 1, '', '');?> 1 hr before  </label>
		<label class="checkbox left"><?=form_checkbox('oneday_before', 1, '', '');?> 24 hour before  </label>
		<label class="checkbox left"><?=form_checkbox('days_before', 1, '', '');?> <input type="text" name="day_before_text" class="days_input"/>days before  </label>
	</div>
        <style>
            .days_input {
                width: 30px !important;
                margin-right: 5px !important;
                height: 20px !important;
                margin-bottom: 0px !important;
                padding: 0px !important;
                line-height: 15px !important;
                padding-left: 3px !important;
            }
        </style>
	<div class="clearfix"></div>
	<?php }?>
	
	<div>
		<label>Description <b class="err err_description"></b></label>
		<textarea class="span12" id="wysiwyg" name="description" readonly><?=$item->description?></textarea>
	</div>
   	
    <div>
		

		<input type="hidden" name="id" value="<?php echo $item->id?>" />
		<label></label>
		<input type="submit" id="submit" class="btn btn-primary" value="Resend" />
		<a href="<?= admin_url('news');?>" class="btn"> Cancel </a>
	</div>
<?=form_close()?>

<?$this->load->view('admin/ck_script');?>

<script type="text/javascript">
	$(function(){
		
		$("#is_event").on('change', function(){
			if($("#is_event").is(":checked")){
			$("#event_dates").show();
			}
			else{
				$("#event_dates").hide();
			}
		});		
		$("#is_email").on('change', function(){
			if($("#is_email").is(":checked")){
			$("#event_email").show();
			}
			else{
				$("#event_email").hide();
			}
		});
		
	});
</script>

<!-- include fineuploader script-->
<script  src="<?php echo asset_url('js/fineuploader/jquery.fineuploader-3.0.js')?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=asset_url('js/fineuploader/fineuploader.css')?>">



<script type="text/javascript">
	
	 $(document).ready(function () {

	 		$('#fine_uploader').fineUploader({
			request: {
				endpoint: admin_url + 'news/upload',
				params: {crsf:crsf}
			},
			validation:{
				acceptFiles: "image/*"
			},
			text: {
				uploadButton: '<i class="icon-upload icon-white"></i> Upload Image'
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
					$("#thumb_url").val(data.file);
					$("#image_url").val(data.bigfile);
					var $img = $('<img>', {
							src : data.file,
							alt : ''							
						});	
					$("#file_cnt").html($img);				
					//B.log(data);
				}
			});		
			
      });

</script>
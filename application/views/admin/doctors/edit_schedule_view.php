
<div class="row">
	<div class="span9">
		<?php echo $calendar;?>
	</div>

	<div class="span3">		
		<h4 class="sch_title">Add Schedule</h4>
		<div class="schedule_message"></div>
		<?=form_open(admin_url('doctors/save_schedule'), 'onsubmit="return SendForm(this);" id="sch_form" ')?>
		<div class="row">
			<div class="span1">
				<label>Date</label>
				<input type="text" name="date_from" class="input-mini" value="" data-mask="9?9"/>
			</div>
			<div class="span2">
				<label>To</label>
				<input type="text" name="date_to" class="input-mini" value=""  data-mask="9?9"/>
			</div>
		</div>

		<div class="row">
			<div class="span1">
				<label >StartTime</label>
				<input type="text" name="start" class="input-mini" value="" data-mask="99:99"/>
			</div>
			<div class="span2">
				<label>EndTime</b></label>
				<input type="text" name="end" class="input-mini" value="" data-mask="99:99"/>
			</div>
		</div>
		<div>
			<label>Duration per consult</label>
			<?=form_dropdown('type', array(15=>"15 min", 20=>"20 min", 30=>"30 min", 60=>"60 min"), 30, 'class="span2"');?>
		</div>
		<div>
			<label>Video Consult</label>
			<input type="text" name="rate" class="span2" value="" />
		</div>
		
		<div>
			<label>Clinic Consult</label>
			<input type="text" name="rate_clinic" class="span2" value="" />
		</div>
		<div>
			<label>Comment</label>
			<textarea name="comment" class="autogrow"></textarea	>
		</div>

		<div>
			<input type="hidden" name="doc_id" value="<?=$doc_id?>" />
			<input type="hidden" name="month" value="<?=$month?>" />
			<input type="hidden" name="year" value="<?=$year?>" />
			<input type="hidden" name="id" value=""/>		
			<input type="submit" value="Save changes" class="btn btn-primary" />
		</div>
		<?=form_close()?>		
	</div>
<div>

<!-- modal popup -->
<div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Slot Details</h3>
	</div>	
	<div class="modal-body">
					
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary save">Save changes</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>
<!-- /modal popup-->

<script type="text/javascript">

	$(function(){
		$("#calendar .remove").on('click', function(){
			var id=$(this).attr('data-id');
			
			var url = admin_url+'doctors/remove_schedule/';
			var $schedule_block = $(this).parent();
		
			$.post(url,	
					{id:id,csrf_test_name:crsf}, 
					function(data){
						if(data.success){
							$schedule_block.fadeOut(300);
						}
						if(data.error){
							alert(data.error);
						}
					}, 
					'json'
				);
		});

		$("#calendar .sch_block").on('click', function(){
			var id=$(this).attr('data-id');
			var day=$(this).attr('data-day');
			var start=$(this).attr('data-start');
			var end=$(this).attr('data-end');
			var type=$(this).attr('data-type');
			var rate=$(this).attr('data-rate');
			var rate_clinic=$(this).attr('data-rate_clinic');
			var comment=$(this).attr('data-comment');

			$form = $("#sch_form");

			$('[name=id]', $form).val(id);
			$('[name=date_from]', $form).val(day).prop('readonly', true);
			$('[name=date_to]', $form).val(day).prop('readonly', true);
			$('[name=start]', $form).val(start);
			$('[name=end]', $form).val(end);
			$('[name=type]', $form).val(type);
			$('[name=rate]', $form).val(rate);
			$('[name=rate_clinic]', $form).val(rate_clinic);
			$('[name=comment]', $form).val(comment);

			

			$('.sch_title').text('Edit Schedule');
		});

		$("#calendar span[data-slot_id]").on('click', function(){
			//alert($(this).length);
			var slot_id = $(this).attr('data-slot_id');
			$('#modal').modal({remote: admin_url+'doctors/edit_slot/'+slot_id})
		});

	});
</script>


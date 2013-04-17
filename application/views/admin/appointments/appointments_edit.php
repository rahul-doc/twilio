<div class="row">
	<div class="span12">
	<?=form_open(admin_url('appointments/save'), 'onsubmit="return SendForm(this);"')?>

		<div>
			<label>Name 1 <b class="err err_patient_id"></b></label>		
			<input type="text" name="patient" class="span6" id="patient" autofocus/>
			<input type="hidden" name="patient_id" />
		</div>

		<div>
			<label>Name 2 <b class="err err_doctor_id"></b></label>		
			<input type="text" name="doctor" class="span6" id="doctor" />
			<input type="hidden" name="doctor_id" />
		</div>

		<div>
			<label>Available Slots <b class="err err_schedule_id"></b></label>
			<?=form_dropdown('schedule_id', array(), '', 'id="schedule_id"');?>
		</div>
		
	    <div>
			<input type="submit" id="submit"  class="btn btn-primary" value="Create" />		
		</div>

	<?=form_close()?>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>

<script type="text/javascript">

	$('#patient').autocomplete({
		source: admin_url+'appointments/autocomplete_patients',
		select: function( event, ui ) {
			if(!ui.item.id){return false;}
			$("[name=patient_id]").val(ui.item.id);	 		
		}
	});

	$('#doctor').autocomplete({
		source: admin_url+'appointments/autocomplete_doctors',
		select: function( event, ui ) {
			
			if(!ui.item.id){return false;}
			
			$("[name=doctor_id]").val(ui.item.id);	

			//retrieve schedules
			var $dest = $("#schedule_id");
			$dest.attr('disabled',true).html('');
			$dest.append('<option value="">Loading...</option>');
			
			$.get(
				admin_url+'appointments/get_schedule',
				{doctor_id: ui.item.id},
				function(data){
					$dest.attr('disabled',false).val('');
					var html="";
					for (var id in data){
						html+='<option value='+id+'>'+data[id]+'</option>';
					}
					$dest.html(html);
				},
				'json'
			);
			
		}
	});

</script>


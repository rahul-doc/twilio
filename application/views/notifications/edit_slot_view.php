<?=form_open(admin_url('doctors/update_slot_status'), 'onsubmit="return SendForm(this);"')?>
	<br>
	<div class="row">
		<div class="span2"><b>Patient:</b> <?=$item->name?></div>
		<div class="span2"><b>ConsultStart:</b> <?=$item->consult_start?></div>
	</div>

	<div class="row">
		<div class="span2"><b>Consult type:</b> <?=$item->type?></div>
		<div class="span2"><b>ConsultEnd:</b>	<?=$item->consult_end?>	</div>
	</div>
	
	<br>
	<div>
		<label><b>Status: </b></label>
		<?=form_dropdown('status', array('pending'=>'Pending', 'confirmed'=>'Confirmed'), $item->status);?>
	</div>	

	<div>	
		<input type="hidden" name="id" value="<?=$item->id?>"/>		
		<input type="submit" value="" class="not-visible" />
	</div>

<?=form_close()?>	
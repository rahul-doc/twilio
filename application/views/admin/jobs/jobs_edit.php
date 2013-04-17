<div class="row">
	<div class="span12">
	<?=form_open(admin_url('jobs/save'), 'onsubmit="return SendForm(this);"')?>

		<div>
			<label>Name <b class="err err_ca01name"></b></label>		
			<input type="text" name="ca01name"  value="<?php echo $item->ca01name?>" autofocus="autofocus" class="span11" />
		</div>

		<div>
			<label>Closing Date <small>(yyyy-mm-dd)</small><b class="err err_ca01closing"></b></label>		
			<input type="text" name="ca01closing"  value="<?php echo date('Y-m-d', $item->ca01closing)?>" class="input" />
		</div>
		
		<div>
			<label>Description <b class="err err_ca01description"></b></label>
			<textarea name="ca01description" class="span12 autogrow"><?=$item->ca01description?></textarea>	
		</div>
		
		<div>
			<label>Text <b class="err err_ca01text"></b></label>
			<textarea name="ca01text" id="wysiwyg" class="span12"><?=$item->ca01text?></textarea>	
		</div>
		
	    <div>
			<hr>
			<input type="hidden" name="id" value="<?php echo isset($item->ca01uin)? $item->ca01uin : ""?>" />		
			<input type="submit" id="submit"  class="btn btn-primary" value="Save" />		
			<a href="<?php echo admin_url('jobs');?>" class="btn"> Cancel </a>
		</div>

	<?=form_close()?>
	</div>
</div>

<?$this->load->view('admin/ck_script');?>


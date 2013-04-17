<?=form_open(admin_url('roles/save'), 'onsubmit="return SendForm(this);"')?>
	<div>
		<label>Code <b class='d'>(max 5 chars)</b> <b class="err err_code"></b></label>
		<input type="text" autofocus="autofocus" class="text small" name="code" maxlength="5" id="code" value="<?=$item->code?>"/>
	</div>
    <div>
		<label>Name <b class="err err_name"></b></label>
		<input type="text" class="text small" name="name" id="name" value="<?=$item->name?>"/>
	</div>
	 <div>
		<label>Description <b class="err err_description"></b></label>
		<textarea class="text medium" name="description" id="description"><?=$item->description?></textarea>
	</div>
   	
    <div>
		<input type="hidden" name="id" value="<?= isset($item->code)? $item->code : ""?>" />
		<label></label>
		<input type="submit" class="btn btn-primary" value="Save" />
		<a href="<?= admin_url('roles');?>" class="btn"> Cancel </a>
	</div>
<?=form_close()?>
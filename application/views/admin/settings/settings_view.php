<?=form_open(admin_url('settings/save'))?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Key</th>
			<th>Value</th>		
		</tr>
	</thead>
	<? foreach($settings as $key => $value):?>
	<tr>
		<td><?=$key?></td>
		<td><input type="text" name="settings[<?=$key?>]" value = "<?=$value?>" class="span9"/></td>		
	</tr>
	<? endforeach?>
</table>

	<input type="submit" class="btn btn-primary" value="Save" />

<?=form_close()?>

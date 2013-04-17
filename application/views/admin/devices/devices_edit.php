<?=form_open(admin_url('devices/save'), 'onsubmit="return SendForm(this);"')?>
	
	<div class="pull-right">
		<label>Avatar <b class="err err_avatar_url"></b></label>
		
		<div id="fine_uploader" style="width:200px">
		    <noscript>
		        <p>Please enable JavaScript to use file uploader.</p>
		        <!-- or put a simple form for upload here -->
		    </noscript>
		</div>			
		<div id="file_cnt">
			<?if($item->avatar_url):?>
				<img src="<?=$item->avatar_url?>" alt="" />
			<?endif?>
		</div>
		<input type="text" readonly="readonly" name="avatar_url" id="avatar_url" value="<?=$item->avatar_url?>" />			
	</div>

	<div>
		<label>Is Active <b class="err err_is_active"></b></label>
		<?=form_dropdown('is_active', array('1'=>'Active', '0'=>'Inactive'), $item->is_active)?>
	</div>

	<div>
		<label>Name<b class="err err_name"></b></label>
		<input type="text" class="span4" name="name" value="<?php echo $item->name?>" autofocus="autofocus"/>
	</div>
	<div>
		<label>2net ID<b class="err err_2net_ID"></b></label>
		<input type="text" class="span4" name="twonetID" value="<?php echo $item->twonetID?>" />
	</div>
	<div>
		<label>Maker<b class="err err_maker"></b></label>
		<?=form_dropdown('maker', $this->config->item('twonet_devices'), $item->maker)?>
	</div>
	<div>
		<label>Distributer <b class="err err_dist"></b></label>
		<input type="text" class="span4" name="distributer_name" value="<?php echo $item->distributer_name?>" />
	</div>

	<div>
		<label>Distributer email <b class="err err_dist_email"></b></label>
		<input type="text" class="span4" name="distributer_email" value="<?php echo $item->distributer_email?>"/>
	</div>
	
		
	<div>
		<label>Distributer Address <b class="err err_dist_contact"></b></label>
		<input type="text" class="span4" name="distributer_address" value="<?php echo $item->distributer_address?>"/>
	</div>
	<div>
		<label>Distributer Telephone <b class="err err_dist_contact"></b></label>
		<input type="text" class="span4" name="distributer_tel" value="<?php echo $item->distributer_tel?>"/>
	</div>
	

	<div>
		<input type="hidden" name="id" value="<?php echo isset($item->id)? $item->id : ""?>" />
		
		<label></label>
		<input type="submit" class="btn btn-primary" value="Save" />
	</div>
<?=form_open()?>

<?$this->load->view('admin/avatar_script')?>
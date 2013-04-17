<?$this->load->helper('form')?>
<div class="row">
<div class="span6">

	<?=form_open(admin_url('auth/save_account'), 'onsubmit="return SendForm(this);"')?>
		<div>
			<label>First Name <b class="err err_first_name"></b></label>
			<input type="text" name="first_name" id="first_name" value="<?php echo $item->first_name?>"/>			
		</div>
	    <div>
			<label>Last Name <b class="err err_last_name"></b></label>
			<input type="text" name="last_name" id="last_name" value="<?php echo $item->last_name?>"/>
			
		</div>
	    <div>
			<label>Email <b class="err err_email"></b></label>
			<input type="text" name="email" id="email" value="<?php echo $item->email?>"/>			
		</div>
		<div>
			<label>Contact Number <b class="err err_contact_no"></b></label>
			<input type="text"   name="contact_no" id="contact_no" value="<?php echo $item->contact_no?>"/>			
		</div>
		<div>
			<label>Username<b class="err err_username"></b></label>
			<input type="text"  name="username" id="username"  autocomplete="off" value="<?php echo $item->username?>"/>
		</div>
	     
	    <div>			
			<input type="submit" class="btn btn-primary" value="Save Changes" />		
		</div>
	<?=form_close()?>
</div>
<div class="span6">
	<h2>Change password</h2>

	<?=form_open(admin_url('auth/change_password'), 'onsubmit="return SendForm(this)"')?>
		<div>
			<label>Current Password <b class="err err_current_password" /></b></label>
			<?=form_password('current_password', '', '')?>
		</div>
		<div>
			<label>New Password <b class="err err_new_password" /></b></label>
			<?=form_password('new_password', '', '')?>
		</div>
		<div>
			<label>Confirm Password <b class="err err_new_password2" /></b></label>
			<?=form_password('new_password2', '', '')?>			
		</div>
		
		<div>		
			<input type="submit" class="btn btn-primary" value="Change Password" />
		</div>
	<?=form_close()?>

</div>
</div>
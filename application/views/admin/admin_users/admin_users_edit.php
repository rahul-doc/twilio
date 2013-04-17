<?=form_open(admin_url('admin_users/save'), 'onsubmit="return SendForm(this);"')?>
	<div>
		<label>First Name <b class="err err_first_name"></b></label>
		<input type="text" class="text small" name="first_name" id="first_name" value="<?php echo $item->first_name?>"/>
	</div>
    <div>
		<label>Last Name <b class="err err_last_name"></b></label>
		<input type="text" class="text small" name="last_name" id="last_name" value="<?php echo $item->last_name?>"/>
		
	</div>
    <div>
		<label>Email <b class="err err_email"></b></label>
		<input type="text" class="text small" name="email" id="email" value="<?php echo $item->email?>"/>
	</div>
	<div>
		<label>Role <b class="err err_role_code"></b></label>
		<?=form_dropdown('role_code', $roles, $item->role_code, 'class="medium"');?>	   
	</div>
	<div>
		<label>Contact Number <b class="err err_contact_no"></b></label>
		<input type="text"  class="text small" name="contact_no" id="contact_no" value="<?php echo $item->contact_no?>"/>
	</div>
	<div>
		<label>Username <b class="err err_username"></b></label>
		<input type="text" class="text small" name="username" id="username"  autocomplete="off" value="<?php echo $item->username?>"/>
		
	</div>
	<div>
		<label>Password <b class="err err_password"></b></label>
		<input type="password" class="text small" name="password" id="password" autocomplete="off" />
		<?php if(isset($item->id)):?><div class="d">Leave blank if you don't want to change password</div> <?php endif?>
	</div>
	

    <div>
		<input type="hidden" name="id" value="<?php echo isset($item->id)? $item->id : ""?>" />
		<label></label>
		<input type="submit" class="btn btn-primary" value="Save" />
		<a href="<?php echo admin_url('admin_users');?>"  class="btn"> Cancel </a>
	</div>
<?=form_open()?>
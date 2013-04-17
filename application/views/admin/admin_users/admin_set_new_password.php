<h2>Set new password</h2>

<?=form_open(admin_url('auth/set_new_password'), 'onsubmit="return SendForm(this)"')?>
	<div>
		<label>Username: <b><?=$user->username?></b></label>
		<label>Email: <b><?=$user->email?></b></label>
	</div>
	<hr>
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
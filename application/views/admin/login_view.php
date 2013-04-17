<!DOCTYPE html>
<html lang="en">
<head>
	<? $this->load->view('admin/admin_header');?> 
</head>
	<body>  	  		
  	  	
  	<div class="container" style="margin-top:10%;">
  		<div class="row">
  		<div class="span4 offset4">
  			<div class="well" style="padding: 30px 40px">
  			<?=form_open(admin_url("auth/try_login"), 'class="" id="frm_login"')?> 
           
				<h2 class="center">Admin Panel</h2>
				
				<div><?=show_message()?></div>

				<div>        
					<label>Username</label>
					<input name="username" type="text" class="span3" id="username" />
				</div>
				
				<div>
					<label>Password</label>
					<input name="password" type="password" class="span3" id="password" />
				</div>
	       	  	
	            <div>
	                <input name="btnLogin" type="submit" class="btn btn-primary" value="LOGIN" />                            
					<a href="javascript:;" class="pull-right" data-toggle="collapse" data-target="#forgot_password">Forgot password?</a>
				</div>
        	<?=form_close()?>

        	<div>
				
				<div id="forgot_password" class="collapse">
					<hr>
					<div id="message"></div>
					<?=form_open(admin_url("auth/forgot_password_request"), 'onsubmit="return SendForm(this)"')?>
						<div>
							<label>Your email</label>
							<input name="email" type="text" class="span3" id="email" />
						</div>
						<div>
			                <input type="submit" class="btn btn-primary" value="Reset password" />                            
						</div>					
					<?=form_close()?>
				</div>
        	</div>
        	</div>
    	
    	</div>
    	
    	<div class="span4"></div>


	<? $this->load->view('admin/admin_footer');?>	
  </body>
</html>


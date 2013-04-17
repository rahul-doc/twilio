<!DOCTYPE html>
<html lang="en">
<head>
	<? $this->load->view('admin/admin_header');?> 
</head>
	<body>  	  		
  	<?if($this->session->userdata('logged')=='admin'):?>
  	 <? $this->load->view('admin/menu_view');?>
    <?endif?>

  	
  	<div class="container">	
  		<? if(isset($title)):?>
  			<h2><?=$title?></h2>
  		<? endif?>
  		<div id="message"><? show_message()?></div>
  		
  		<? $this->load->view($view_file)?>
  	</div>


	<? $this->load->view('admin/admin_footer');?>	
  </body>
</html>
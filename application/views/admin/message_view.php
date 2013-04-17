<div class="row">
	<div id="general_info">
	<div class="alert <?=isset($success)? 'alert-success' :'alert-error'?>">
		<?php if(isset($error)):?>
		<p><?php echo $error?></p>

		<?php elseif(isset($success)):?>
		<p><?php echo $success?></p>
		<?php endif?>
	</div>
	<?show_message()?>
	</div>
</div>
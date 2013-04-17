<? $this->load->helper('form')?>
<div class="submenu">
	<?php foreach($roles as $url=>$label){
		echo anchor($url, $label) . " | ";
	}?>
</div>

<? if(isset($funcs)):?>
	<h3 class="center"><?=$roles["admin/permissions/index/$role_code"]?> permissions</h3>

	<?=form_open(admin_url('permissions/save'), 'onsubmit="return SendForm(this)"')?>
		<div class="row">
			<?foreach($funcs as $controller => $func):?>
			<div class="span3 man">
				<div class="well well-small">
				<h4><?=$controller?></h4>
				<?foreach($func as $f):?>
					<?$id = $controller.'/'.$f?>
					 <label class="checkbox">				
						<?=form_checkbox('perm[]', $id, isset($items[$controller.'/'.$f]), 'id="'.$id.'"')?>
						<?=$f?>
					</label>
					
				<?endforeach?>
				</div>
			</div>
			<?endforeach?>
		</div>

		<div class="clearfix"></div><br />
		<div class="center">
			<input type="hidden" name="role_code" value="<?=$role_code?>" />
			<input type="submit" class="btn btn-primary" value="Update Permissions" />
		</div>	
	<?=form_close()?>
<?endif?>


<script type="text/javascript" src="<?=asset_url('admin/js/jquery.masonry.min.js')?>"></script>

<script type="text/javascript">
	$(function(){		
		$('.row').masonry({
			itemSelector: '.man'
		});		
	});
</script>

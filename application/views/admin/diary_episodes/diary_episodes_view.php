<?php $this->load->helper('form')?>
<div><strong>Patient:</strong> <?=$patient->name?></div>
<div class="filter">
	<?=form_open(admin_url('diary_episodes/get_ajax_list'), 'id="filter_form"')?>
		<input type="hidden" name="sort_col" id="sort_col" value="<?php  echo f('sort_col');?>" />
		<input type="hidden" name="sort_dir" id="sort_dir" value="<?php  echo f('sort_dir');?>" />
		<input type="hidden" name="filter" value="1" />
		<input type="hidden" name="filter" value="<?=$acc_id?>" />
	<?=form_close()?>
</div>


<div id="list">
<?php $this->load->view('admin/diary_episodes/diary_episodes_list');?>
</div>



<script type="text/javascript">
<!--

	 function DeleteItem(id, obj){
	   	url = admin_url+"diary_episodes/delete";
		SimpleDelete(id, obj, url);
	 }

// -->
</script>
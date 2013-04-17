<?php $this->load->helper('form')?>

<div class="filter">
	<?=form_open(admin_url('diary_entries/get_ajax_list'), 'id="filter_form"')?>
		<input type="hidden" name="sort_col" id="sort_col" value="<?php  echo f('sort_col');?>" />
		<input type="hidden" name="sort_dir" id="sort_dir" value="<?php  echo f('sort_dir');?>" />
		<input type="hidden" name="filter" value="1" />
		<input type="hidden" name="filter" value="<?=$episode_id?>" />
	<?=form_close()?>
</div>

<div id="list">
<?php $this->load->view('admin/diary_entries/diary_entries_list');?>
</div>


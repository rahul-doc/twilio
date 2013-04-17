<?php $this->load->helper('form')?>

<a id="add" href="<?php echo admin_url('news/add')?>" class="btn">Add New</a>

<div class="filter">
	<?=form_open(admin_url('news/get_ajax_list'), 'id="filter_form"')?>
		<input type="hidden" name="sort_col" id="sort_col" value="<?php  echo f('sort_col');?>" />
		<input type="hidden" name="sort_dir" id="sort_dir" value="<?php  echo f('sort_dir');?>" />
		<input type="hidden" name="filter" value="1" />
	<?=form_close()?>
</div>


<div id="list">
<?php $this->load->view('admin/news/news_list');?>
</div>



<script type="text/javascript">
<!--

	 function DeleteItem(id, obj){
	   	url = admin_url+"news/delete";
		SimpleDelete(id, obj, url);
	 }

// -->
</script>
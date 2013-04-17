<?php $this->load->helper('form')?>
<div class="row">
	<?=form_open(admin_url('devices/get_ajax_list'), 'id="filter_form" class="form-search" onsubmit="return SendForm(this)"' )?>	
   <div class="span2">
		<a  href="<?php echo admin_url('devices/add')?>" class="btn" id="add">Add New</a>
	</div>
	<div class="span10 text-right">
	   
	   <div class="input-append">
			<input type="text" class="search-query" placeholder="search item..." name="filter_name" value="">
			<button type="button" id="clear_btn" tabindex="-1" class="btn clear_btn">X</button>
			
		</div>
		<button type="submit" class="btn">Search</button>
	   

	    <?php $filter_options = array('2'=>'All Users', '1'=>'Active Users', '0'=>'Inactive Users');?>
        <?php echo form_dropdown('filter_status', $filter_options, f('status'), 'id="filter_status"');?>
		<input type="hidden" name="sort_col" id="sort_col" value="<?php  echo f('sort_col');?>" />
		<input type="hidden" name="sort_dir" id="sort_dir" value="<?php  echo f('sort_dir');?>" />
		<input type="hidden" name="filter" value="1" />
	</div>		
			
	<?=form_close()?>

</div>


<div id="list">
<?php $this->load->view('admin/devices/devices_list');?>
</div>



<script type="text/javascript">
<!--

	$(function(){
		$("#filter_status").change(function(){SendFilter()});
		$("#clear_btn").on('click', function(){$("input[name=filter_name]").val(''); SendFilter();})

	});


	 function DeleteItem(id, obj){
	   	url = admin_url+"devices/delete";
		SimpleDelete(id, obj, url);
	 }

	 function Activate(id, obj)
	 {
	 	SimpleActivate(id, obj, admin_url+"devices/activate");
	 }

// -->
</script>
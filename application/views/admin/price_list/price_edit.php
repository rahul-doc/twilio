<style type="text/css">
div#autoSuggestionsList{
	width:600px;
}
div#autoSuggestionsList li{
	cursor:pointer;
	list-style:none;
	margin:0 0 0 20px;
	padding:5px;
	background-color:#CCC;
	border-bottom:1px solid #999;
}
span.sp_name{
	overflow:hidden;
}
span.sp_email{
	left:360px;
	position:absolute;
	overflow:hidden;
}
select[name="currency"]{
	width:80px;	
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	
	jQuery('#suggestions').hide();

	var product_type =  jQuery(".product_type:checked").val();
	if(product_type == 'other')
		jQuery('#product_type_other').show();
	else
	{
		jQuery('#product_type_other').hide();
		jQuery('#product_type_other').val('');
	}
	jQuery('.product_type').change(function(){
		if(jQuery(this).val() == 'other')
			jQuery('#product_type_other').show();
		else
		{
			jQuery('#product_type_other').hide();
			jQuery('#product_type_other').val('');
		}
	});
	
	jQuery('#provider').click(function()
	{
		lookup(jQuery(this).val());
	});

	jQuery('#provider').keyup(function()
	{
		lookup(jQuery(this).val());
	});
	
	function lookup(inputString) {
		if(inputString.length == 0) {
			jQuery('#suggestions').hide();
		} else {
			jQuery.ajax({
				url:'<?php echo admin_url('price_list/autocomplete/');?>' + '/' + inputString,
				type:'GET',
				success:function(response){
					if(response.length > 0) {
                		jQuery('#suggestions').show();
                		jQuery('#autoSuggestionsList').html(response);
            		} else {
                		jQuery('#suggestions').hide();
					}
				},
				error:function(xhr,status,error){
					alert(xhr.responseText);
				},
			});
		}
	}
	
	jQuery('div#autoSuggestionsList li').live('click',function(){
		var did = this.id;
		fill(did.replace('doc_',''));
	});
	
	function fill(thisValue) {
    	jQuery('#acc_id').val(thisValue);
		jQuery('#provider').val(jQuery('#doc_'+ thisValue + ' span.sp_name').text());
    	setTimeout("jQuery('#suggestions').hide();", 200);
	}   
});
</script>
<?=form_open(admin_url('price_list/save'), 'onsubmit="return SendForm(this);"')?>
	
	<div>
		<label>ID</label>
		<input type="text" readonly="readonly" class="span1" name="price_id" value="<?php echo $item->price_id?>"/>
	</div>
    
	<div>
		<label>Description <b class="err err_description"></b></label>
		<input type="text" class="span4" id="description" name="description" value="<?php echo $item->description?>"/>
	</div>

	<div>
		<label>Product/Service Type<b class="err err_product_type"></b></label>
        <input type="radio" class="product_type" name="product_type" value="consultation" <?php if($item->product_type == 'consultation') echo 'checked="checked"'; ?> />&nbsp;Consultation&nbsp;&nbsp;&nbsp;
        <input type="radio" class="product_type" name="product_type" value="device" <?php if($item->product_type == 'device') echo 'checked="checked"'; ?>/>&nbsp;Device&nbsp;&nbsp;&nbsp;
        <input type="radio" class="product_type" name="product_type" value="medicine" <?php if($item->product_type == 'medicine') echo 'checked="checked"'; ?>/>&nbsp;Medicine&nbsp;&nbsp;&nbsp;
        <input type="radio" class="product_type" name="product_type" value="procedure" <?php if($item->product_type == 'procedure') echo 'checked="checked"'; ?>/>&nbsp;Procedure&nbsp;&nbsp;&nbsp;
		<input type="radio" class="product_type" name="product_type" value="service" <?php if($item->product_type == 'service') echo 'checked="checked"'; ?>/>&nbsp;Service&nbsp;&nbsp;&nbsp;
		<input type="radio" class="product_type" name="product_type" value="other" <?php if($item->product_type == 'other') echo 'checked="checked"'; ?>/>&nbsp;Other&nbsp;&nbsp;
        <input type="text" class="span3" id="product_type_other" name="product_type_other" style="display:none;" value="<?php echo $item->product_type_other;?>"/>
	</div>

	<div style="margin-top:10px;">
		<label>Doctor/Provider ID <b class="err err_acc_id"></b></label>
		<input type="text" readonly="readonly" class="span1" id="acc_id" name="acc_id" value="<?php echo $item->acc_id; ?>"/>
	</div>

	<div>
		<label>Provider <b class="err err_provider"></b></label>
		<input type="text" class="span4" id="provider" name="provider" value="<?php echo $item->provider; ?>"/>&nbsp;&nbsp;<a href="<?php echo admin_url('doctors/add/');?>">Add New Provider</a>
        <div id="suggestions">
            <div id="autoSuggestionsList"></div><br/>
        </div>
	</div>

	<div>
		<label>Unit Price <b class="err err_unit_price"></b></label>
		<input type="text" class="span1" name="currency" readonly="readonly" value="<?php echo $this->config->item('currency'); ?>" />&nbsp;<input type="text" class="span1" name="unit_price" value="<?php echo $item->unit_price?>"/>&nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" name="recurring_monthly" <?php if($item->recurring_monthly) echo ' checked="checked"'; ?> />&nbsp;&nbsp;Recurring Monthly
	</div>

	<div>
		<label>Date Added</label>
		<input type="text" readonly="readonly" class="span2" name="date_added" value="<?php echo empty($item->price_id) ? date('Y-m-d') : $item->date_added; ?>"/>
	</div>

	<div>
		<input type="checkbox" value="1" name="is_active" <?php if($item->is_active) echo ' checked="checked"'; ?> />&nbsp;&nbsp;Active
	</div>

	<div style="margin-top:30px;">
		<label></label>
		<input type="submit" class="btn btn-primary" value="Save" />
	</div>
<?=form_open()?>
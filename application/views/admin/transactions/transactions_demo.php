<?php $this->load->helper('form')?>
<style type="text/css">
div#suggestions{
	width:600px;
	margin-top:-8px;
}
div#autoSuggestionsList{
	width:600px;
}
div#autoSuggestionsList li{
	cursor:pointer;
	list-style:none;
	margin:0;
	padding:5px;
	background-color:#F2F2F2;
	border-bottom:1px solid #999;
}
div#autoSuggestionsList li:hover{
	background-color:#F8F8F8;
}

span.sp_name{
	overflow:hidden;
}
span.sp_email{
	left:360px;
	position:absolute;
	overflow:hidden;
}
/*select[name="currency"]{
	width:80px;	
}*/
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	
	jQuery('#suggestions').hide();

	jQuery('#patient').click(function()
	{
		lookup(jQuery(this).val());
	});
	jQuery('#patient').keyup(function()
	{
		lookup(jQuery(this).val());
	});
	
	function lookup(inputString) {
		if(inputString.length == 0) {
			jQuery('#suggestions').hide();
		} else {
			jQuery.ajax({
				url:'<?php echo admin_url('transactions/autocomplete/');?>' + '/' + inputString,
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
		jQuery('#patient').val(jQuery('#doc_'+ thisValue + ' span.sp_name').text());
    	setTimeout("jQuery('#suggestions').hide();", 200);
	}   
});
</script>
<div class="row">
	<div class="span10 text-left" style="background-color:#CCC; padding:5px;">
		<?php print_r( $response); ?>
	</div>
</div>
<div class="row" <?php echo $recurring_product ? ' style="display:none;"' : '';?>>
	<?=form_open(admin_url('transactions/demo') )?>	
        <div class="span10 text-left">
            
            <div style="margin-top:10px;">
                <label>Product / Service <b class="err err_product"></b></label>
                <?=form_dropdown('product', $product , $item->product)?>
            </div>
    
            <div>
                <label>Qty <b class="err err_qty"></b></label>
                <input type="text" class="span1" name="qty" value="1"/>
            </div>
    
            <div>
                <label>Patient Account ID <b class="err err_acc_id"></b></label>
                <input type="text" readonly="readonly" class="span1" id="acc_id" name="acc_id" />
            </div>
        
            <div>
                <label>Patient <b class="err err_patient"></b></label>
                <input type="text" class="span4" id="patient" name="patient" value="<?php echo $item->patient; ?>"/>
                <div id="suggestions">
                    <div id="autoSuggestionsList"></div><br/>
                </div>
            </div>
            
            <div>
                <input type="checkbox" name="check_balance" />&nbsp;Check Balance
            </div>
        
            <div style="margin-top:30px;"><?php echo form_submit('submit', 'Process')?></div>
    
        </div>
	<?=form_close(); ?>
</div>
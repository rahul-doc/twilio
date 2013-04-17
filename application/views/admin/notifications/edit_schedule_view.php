<script type="text/javascript">
    function check_add_list()
    {
	
        //alert('asim');
        //alert($("#fromBox :selected").val());
        var select_text_value = $("#fromBox :selected").val();
        var select_text = $("#fromBox :selected").text();
        // alert("aaaa"+select_text_value.length)
        /*for(var no=0;no<select_text_value.length;no++){
                        var obj = arrayOfItemsToSelect[no];
                            alert(no);
                        for(var no2=0;no2<obj.options.length;no2++){
                        $('#toBox').append('<option value="'+select_text_value+'" >'+select_text+'</option>');
				
                        }
                }*/
        //items.push($(this).val());
        //alert($('#get_value').val());
        //$('#fromBox  option:selected').remove();
        $('#fromBox option:selected').each(function(){ 
            //alert($(this).val());
            $('#toBox').append('<option value="'+$(this).val()+'" >'+$(this).text()+'</option>');
	  
            // $('#fromBox').remove('<option value="'+$(this).val()+'" >'+$(this).text()+'</option>');
	  
            if($('#get_value').val()!=null && $('#get_value').val()!='' )
            {
                $('#get_value').val($('#get_value').val()+","+$(this).val());
		
            }
            else
            {
                $('#get_value').val($(this).val());
            }
  
        });

        $('#fromBox  option:selected').remove();
        $('#toBox option').prop('selected',true); 

    }

    function check_del_list()
    {
        // get Item in Text area
        var OptionList  = document.getElementById("get_value");
        var new_rest_list = '';
        var OptionArray = new Array();
        // Split into comma
        OptionArray = OptionList.value.split(",");
        $('#toBox option:selected').each(function(){ 

            $('#fromBox').append('<option value="'+$(this).val()+'" >'+$(this).text()+'</option>');
            // Find Value to remove in Text area
            OptionArray.splice($.inArray($(this).val(), OptionArray),1)
            new_rest_list = OptionArray.join(',');
            $('#get_value').val(new_rest_list);
        });
        $('#toBox  option:selected').remove();
        $('#toBox option').prop('selected',true); 

    }	
    
    

$(function(){
	var active = $("#Datatype .active").val();
        	
               
        $("#clear_btn").live('click', function(){$("#searchQuery").val('')
                
                url = admin_url+"groups/searchGroupsList";
                var objectId='fromBox';
                SearchQuery(objectId,'', url,'all');
        });
        $(".searchType").live('click', function(){
                active = $(this).val();
                url = admin_url+"groups/searchGroupsList";
                var objectId='fromBox';
                SearchQuery(objectId,$("#searchQuery").val(), url,active);
        });
        $("#searchQuery").live('keyup',function(){
                
                url = admin_url+"groups/searchGroupsList";
                var objectId='fromBox';
                SearchQuery(objectId,$(this).val(), url,active);
        });
    
	});
</script>
<div class="row">
    <?php $this->load->helper('form') ?>
    <?= form_open(admin_url("groups/assigndoclist/$group->id"), 'id="form"') ?>	

    <table class="tablesorter" cellpadding="0" cellspacing="0" >
        <tbody><tr>
                <td></td>
            </tr>
            <tr>



                <td> 
                    <div class="input-append" style="margin-left: 40px;">
			<input type="text" placeholder="Type something…" id="searchQuery" >
			<button type="button" id="clear_btn" tabindex="-1" class="btn clear_btn" >X</button>
			<div class="btn-group" data-toggle="buttons-radio" id="Datatype">
                        <button type="button" class="btn searchType active" id="all" value="all">All</button>
                        <button type="button" class="btn searchType" id="doc" value="doctor">Doctor's</button>
                        <button type="button" class="btn searchType" id="pat" value="patient">Patient's</button>
                        
                        </div>   
                        
		</div>
		
                    <select name="fromBox[]" id="fromBox" multiple="multiple" size="12" style="width:390px;margin:35px;">

                        <?php
                        foreach ($data['not']->result() as $list) {
                            ?>
                            <option value="<?php echo $list->id; ?>"><?php echo ($list->account_group == 'doctor') ? (strpos($list->name, 'Dr') !== false) ? $list->name : "Dr. $list->name" : "Pa. $list->name"; ?></option>
                         <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <a onclick="check_add_list();" style="cursor:pointer;"  class="btn btn-mini">Add<i class="icon-chevron-right"></i></a><br/><br/>
                    <a onclick="check_del_list();" style="cursor:pointer;" class="btn btn-mini"><i class="icon-chevron-left"></i>Remove</a>
                </td>
                <td> 
                    <select type="text" name="toBox[]" id="toBox" multiple="multiple" size="12" style="width:300px; margin:0 30px;">
                        <?php
                        foreach ($data['yes']->result() as $rlist) {
                            ?>

                            <option value="<?php echo $rlist->id; ?>"><?php echo $rlist->name . '   ( ' . $list->account_group . ' )'; ?></option>

                            <?php
                        }
                        ?>
                </td>   
            </tr>       
        </tbody>        
    </table>
    <textarea id="get_value" name="get_value" style="display:none;" ><?php //echo $a;  ?></textarea>
    <input type="hidden" name="group_id" id="list_id" value="<?php echo $group->id; ?>" />
    <input type="submit" name="submit" id="submit" value="submit" class="btn btn-primary" style="margin: 0 10px 0 35px;" />
    <a href="<?=admin_url('groups')?>">Cancel</a>
    <?= form_close() ?>





</div>







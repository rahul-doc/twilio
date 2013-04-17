<?=form_open(admin_url('groups/save'),'onsubmit="return SendForm(this);"')?>
	
	<?php if(!empty($docList)){?>
        <div class="span5 pull-right">
            <h3>Doctor's List</h3>
            <ol>
  
            <?php foreach($docList as $dl):
                
                echo "<li>";
            if($dl->account_group == 'doctor')
                    echo $dl->name;
                else
                 echo 'Pa. '.$dl->name;
                echo "</li>";
            endforeach;
            ?>
            </ol>
	</div>
        <?php } ?>
        <div>
		<label>Is Active <b class="err err_status"></b></label>
		<?=form_dropdown('status', array('1'=>'Active', '0'=>'Inactive'), $account[0]->status)?>
	</div>

	<div>
		<label>Name<b class="err err_name"></b></label>
		<input type="text" class="span4" name="name" value="<?php echo isset($account[0]->name)? $account[0]->name : ""?>" autofocus="autofocus"/>
                <?php echo form_error('name'); ?>
	</div>

	<div>
		<label>Description <b class="err err_description"></b></label>
		<input type="text" class="span4" name="description" value="<?php echo isset($account[0]->description)? $account[0]->description : ""?>"/>
                <?php echo form_error('description'); ?>
	</div>
        <?php if(@$admins){?>
        <div>
		<label>Admin <b class="err err_status"></b></label>
		<?=form_dropdown('admin', $admins, $account[0]->admin, 'class="medium"');?>
	</div>
        <?php } ?>

	
	
	
		
	<div class="center">
		<input type="hidden" name="id" value="<?php echo isset($account[0]->id)? $account[0]->id : ""?>" />
		<input type="submit" class="btn btn-primary" value="<?php echo isset($account[0]->id)? "Update" : "Save"?>" />
		<a href="<?=admin_url('groups')?>">Cancel</a>
	</div>






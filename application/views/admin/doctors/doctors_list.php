<table class="table table-striped">
<thead>
<tr>
	<th><a href="javascript:;" onclick="Sort('acc_id')">Id</a></th>
	<th>Avatar</th>
	<th><a href="javascript:;" onclick="Sort('name')">Name</a></th>
	<th><a href="javascript:;" onclick="Sort('email')">Email</a></th>	
	<th><a href="javascript:;" onclick="Sort('is_active')">Active</a></th>
	<th>Options</th>
</tr>
</thead>
<?php foreach($items as $i=> $item):?>
<tr>
	<td><?php echo $item->acc_id?></td>
	<td><?php if($item->avatar_url):?>
			<img src="<?=$item->avatar_url?>" alt="<?=$item->name?>" class="avatar_list" />
		<?php endif?>
	</td>
	<td><?php echo anchor(admin_url("doctors/edit/$item->acc_id"), $item->name)?></td>
	<td><?php echo $item->email?></td>	
	<td>
		<a href="javascript:;" onclick="Activate('<?php echo $item->id?>', this)" title="activate/deactivate"
				class="<?php echo $item->is_active ? 'icon-ok':'icon-off'?>"></a>
	</td>
	<td>
		<a href="<?=admin_url("doctors/edit_schedule/$item->acc_id")?>" class="icon-calendar" title="edit schedule"></a>
		<a href="<?php echo admin_url("doctors/edit/".$item->id);?>" class="icon-edit" title="edit"></a>		
		<a href="javascript:;" onclick="DeleteItem(<?php echo $item->id?>, this)" class="icon-remove" title="remove"></a>
	</td>
</tr>
<?php endforeach;?>
<?php if(count($items)==0):?>
	<tr>
    	<td colspan="20" class="no_data">No data found</td>
	</tr>
<?php endif?>
</table>

<div class="row">
	<div class="span10">		
		<?php echo $this->pagination->create_links();?>		
	</div>
	<div class="span2">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
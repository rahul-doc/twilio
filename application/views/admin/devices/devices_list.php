<table class="table table-striped">
<thead>
<tr>
	<th><a href="javascript:;" onclick="Sort('Id')">Id</a></th>
	<th>Avatar</th>
	<th><a href="javascript:;" onclick="Sort('name')">Name</a></th>
	<th><a href="javascript:;" onclick="Sort('distributer_name')">Distributer</a></th>
	<th><a href="javascript:;" onclick="Sort('2net ID')">2net ID</a></th>
	<th><a href="javascript:;" onclick="Sort('is_active')">Active</a></th>
	<th>Options</th>
</tr>
</thead>
<?php foreach($items as $i=> $item):?>
<tr>
	<td><?php echo $item->id?></td>
	<td><?php if($item->avatar_url):?>
			<img src="<?=$item->avatar_url?>" alt="<?=$item->name?>" class="avatar_list" />
		<?php endif?>
	</td>
	<td><?php echo anchor(admin_url("devices/edit/$item->id"), $item->name)?></td>
	<td><?php echo $item->distributer_name?></td>
	<td><?php echo $item->twonetID?></td>
	<td><a href="javascript:;" onclick="Activate('<?php echo $item->id?>', this)" title="activate/deactivate"
				class="<?php echo $item->is_active ? 'icon-ok':'icon-off'?>"></a>
	</td>
	<td>
		<a href="<?php echo admin_url("device_readings/index/".$item->id);?>" class="icon-tasks" title="Episodes"></a>		
		<a href="<?php echo admin_url("devices/edit/".$item->id);?>" class="icon-edit" title="edit"></a>		
		<a href="javascript:;" onclick="DeleteItem(<?php echo $item->id?>, this)" class="icon-remove" title="remove"></a>
	</td>
</tr>
<?php endforeach;?>
<?php if(count($items)==0):?>
	<tr>
    	<td colspan="20" class="no_data">No data defined</td>
	</tr>
<?php endif?>
</table>

<div class="row">
	<div class="span10">		
		<?php echo $this->pagination->create_links();?>		
	</div>
	<div class="span2 text-right">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
<table class="table table-striped">
<thead>
<tr>
	<th><a href="javascript:;" onclick="Sort('id')">Id</a></th>
	<th><a href="javascript:;" onclick="Sort('username')">Username</a></th>
	<th><a href="javascript:;" onclick="Sort('first_name')">First Name</a></th>
	<th><a href="javascript:;" onclick="Sort('last_name')">Last Name</a></th>
	<th><a href="javascript:;" onclick="Sort('email')">Email</a></th>
	<th><a href="javascript:;" onclick="Sort('role_code')">Role</a></th>
	<th>Options</th>
</tr>
</thead>
<?php foreach($items as $i=> $item):?>
<tr>

	<td><?php echo $item->id?></td>
	<td><?php echo $item->username?></td>
	<td><?php echo $item->first_name?></td>
	<td><?php echo $item->last_name?></td>
	<td><?php echo $item->email?></td>
	<td><?php echo $item->role_code?></td>
	<td>
		<a href="<?php echo site_url("admin/admin_users/edit/".$item->id);?>" class="icon-edit" title="edit"></a>
		<a href="javascript:;" onclick="Activate('<?php echo $item->id?>', this)" title="activate/deactivate"
				class="<?php echo $item->active ? 'icon-ok':'icon-off'?>"></a>
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
	<div class="span2">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
<table class="table table-striped">
<tr>
	<th><a href="javascript:;" onclick="Sort('code')">Code</a></th>
	<th><a href="javascript:;" onclick="Sort('name')">Name</a></th>
	<th><a href="javascript:;" onclick="Sort('description')">Description</a></th>
	<th class="options">Options</th>
</tr>
<?php foreach($items as $i=> $item):?>
<tr>
	<td><?php echo anchor('admin/roles/edit/'.$item->code, $item->code)?></td>
	<td><?php echo $item->name?></td>
	<td><?php echo $item->description?></td>
	<td>
		<a href="<?php echo site_url("admin/roles/edit/".$item->code);?>" class="icon-edit" title="edit"></a>
		<a href="javascript:;" onclick="DeleteItem(<?php echo $item->code?>, this)" class="icon-remove" title="remove"></a>
	</td>
</tr>
<?php endforeach;?>
<?php if(count($items)==0):?>
	<tr>
    	<td colspan="5" class="no_data">No data defined</td>
	</tr>
<?php endif?>
</table>

<div class="row">
	<div class="span10">		
		<?php echo $this->pagination->create_links();?>		
	</div>
	<div class="span2">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
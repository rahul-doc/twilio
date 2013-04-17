<table class="table table-striped">
	<thead>
	<tr>
		<th><a href="javascript:;" onclick="Sort('pr01uin')">Id</a></th>	
		<th><a href="javascript:;" onclick="Sort('pr01name')">Name</a></th>
		<th><a href="javascript:;" onclick="Sort('pr01publishdt')">Publish Date</a></th>	
		<th><a href="javascript:;" onclick="Sort('pr01disabled')">Active</a></th>	

		<th class="options">Options</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($items as $item):?>
	<tr>
		<td><?php echo $item->pr01uin?></td>
		<td><a href="<?=admin_url("press/edit/$item->pr01uin")?>" ><?php echo $item->pr01name?></a></td>
		<td><?php echo $item->pr01publishdt?></td>	
		<td>
			<a href="javascript:;" onclick="Activate('<?php echo $item->pr01uin?>', this)" title="activate/deactivate"
				class="<?php echo $item->pr01disabled ? 'icon-off':'icon-ok'?>"></a>
		</td>	
		<td>
			<a href="<?php echo site_url("admin/press/edit/".$item->pr01uin);?>" class="icon-edit" title="edit"></a>
			
			<a href="javascript:;" onclick="DeleteItem(<?php echo $item->pr01uin?>, this)" class="icon-remove" title="remove"></a>
		</td>
	</tr>
	<?php endforeach;?>
	<?php if(count($items)==0):?>
		<tr>
	    	<td colspan="20" class="no_data">No data found</td>
		</tr>
	<?php endif?>
	</tbody>
</table>

<div class="row">
	<div class="span10">		
		<?php echo $this->pagination->create_links();?>		
	</div>
	<div class="span2">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
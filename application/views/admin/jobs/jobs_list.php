<table class="table table-striped">
	<thead>
	<tr>
		<th><a href="javascript:;" onclick="Sort('ca01uin')">Id</a></th>	
		<th><a href="javascript:;" onclick="Sort('ca01name')">Name</a></th>
		<th><a href="javascript:;" onclick="Sort('ca01closing')">Closing Date</a></th>	
		<th><a href="javascript:;" onclick="Sort('ca01disabled')">Active</a></th>	
		<th class="options">Options</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($items as $item):?>
	<tr>
		<td><?php echo $item->ca01uin?></td>
		<td><a href="<?=admin_url("jobs/edit/$item->ca01uin")?>" ><?php echo $item->ca01name?></a></td>
		<td><?php echo date('Y-m-d', strtotime($item->ca01closing))?></td>	
		<td>
			<a href="javascript:;" onclick="Activate('<?php echo $item->ca01uin?>', this)" title="activate/deactivate"
				class="<?php echo $item->ca01disabled ? 'icon-off':'icon-ok'?>"></a>
		</td>	
		<td>
			<a href="<?php echo site_url("admin/jobs/edit/".$item->ca01uin);?>" class="icon-edit" title="edit"></a>
			<a href="javascript:;" onclick="DeleteItem(<?php echo $item->ca01uin?>, this)" class="icon-remove" title="remove"></a>
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
<table class="table table-striped">
<thead>
<tr>
	<th><a href="javascript:;" onclick="Sort('price_id')">Price ID</a></th>
	<th><a href="javascript:;" onclick="Sort('description')">Description</a></th>
	<th>Type</th>
	<th>Provider</th>
	<th>Unit Price</th>
	<th>Recurring</th>
	<th>Active</th>
	<th>Date Added</th>
	<th>Options</th>
</tr>
</thead>
<?php foreach($items as $i=> $item):?>
<tr>
	<td><?php echo $item->price_id?></td>
	<td><?php echo anchor(admin_url("price_list/edit/$item->price_id"), $item->description, ''); ?></td>
	<td><?php echo $item->product_type=='other'? ucfirst($item->product_type_other) : ucfirst($item->product_type); ?></td>
	<td><?php echo $item->provider?></td>
	<td><?php echo $this->config->item('currency') . number_format($item->unit_price,2); ?></td>
	<td><?php echo !empty($item->recurring_monthly) ? 'Yes' : 'No'; ?></td>
	<td><?php echo !empty($item->is_active) ? 'Yes' : 'No'; ?></td>
	<td><?php echo $item->date_added?></td>
	<td>
		<a href="<?php echo admin_url("price_list/edit/".$item->price_id);?>" class="icon-edit" title="edit"></a>		
		<a href="javascript:;" onclick="DeleteItem(<?php echo $item->price_id; ?>, this)" class="icon-remove" title="remove"></a>
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
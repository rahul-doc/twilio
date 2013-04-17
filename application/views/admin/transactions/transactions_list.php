<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="javascript:;" onclick="Sort('id')">Id</a></th>	
			<th><a href="javascript:;" onclick="Sort('name')">Account</a></th>
			<th><a href="javascript:;" onclick="Sort('type')">Type</a></th>				
			<th><a href="javascript:;" onclick="Sort('amount')">Amount</a></th>				
			<th><a href="javascript:;" onclick="Sort('amount')">Commission</a></th>				
			<th><a href="javascript:;" onclick="Sort('from_name')">FromAccount</a></th>				
			<th><a href="javascript:;" onclick="Sort('txn_time')">TransTime</a></th>				
			<th><a href="javascript:;" onclick="Sort('last_updated')">LastUpdated</a></th>				
		</tr>
	</thead>
	<tbody>
		<?php foreach($items as $item):?>
			<tr>
				<td><?php echo $item->id?></td>
				<td><?php echo $item->name?> <span class="d">(<?=$item->email?>)</span></td>		
				<td><?php echo ucfirst($item->type) ;?></td>		
				<td><?php echo $this->config->item('currency') . number_format($item->amount,2); ?></td>		
				<td><?php echo $this->config->item('currency') . number_format($item->commission,2); ?></td>		
				<td><?php echo $item->from_name?> <span class="d">(<?=$item->from_email?>)</span></td>		
				<td><?php echo date('Y-m-d H:i:s', $item->txn_time)?></td>		
				<td><?php echo $item->last_updated?></td>		
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
	<div class="span2 text-right">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
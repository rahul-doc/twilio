<table class="table table-striped">
<tr>
	<th><a href="javascript:;" onclick="Sort('id')">Id</a></th>
	<th><a href="javascript:;" onclick="Sort('title')">Title</a></th>
	<th><a href="javascript:;" onclick="Sort('list_start_date')">List Start Date</a></th>
	<th><a href="javascript:;" onclick="Sort('list_end_date')">List End Date</a></th>
	<th><a href="javascript:;" onclick="Sort('is_event')">Type</a></th>
	<th class="options">Options</th>
</tr>
<?php foreach($items as $i=> $item):?>
<tr>
	<td><?php echo $item->id?></td>
	<td><?php echo anchor(admin_url('news/edit/'.$item->id), $item->title)?></td>
	<td><?php echo $item->list_start_date?></td>
	<td><?php echo $item->list_end_date?></td>
	<td><?php echo $item->is_event ? "Event" : "News"?></td>
	<td>
		<a href="<?php echo admin_url("news/edit/".$item->id);?>" class="icon-edit" title="edit"></a>
		<a href="javascript:;" onclick="DeleteItem(<?php echo $item->id?>, this)" class="icon-remove" title="remove"></a>
                <a href="javascript:;"  class="icon-repeat" title="resend"></a>
                
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
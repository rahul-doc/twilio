<?$this->load->helper('text')?>
<table class="table table-striped">
	<thead>
	<tr>
		<th width="30"><a href="javascript:;" onclick="Sort('id')">Id</a></th>	
		<th width="50"><a href="javascript:;" onclick="Sort('type')">Type</a></th>		
		<th>Transcript</th>
		<th class="options">Options</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach($items as $item):?>
	<tr>
		<td><?php echo $item->id?></td>
		<td><a href="<?=admin_url("records/edit/$item->id")?>" ><?php echo $item->type?></a></td>		
		<td><?=word_limiter($item->transcript, 50)?></td>
		<td>
			<a href="<?php echo site_url("admin/records/edit/".$item->id);?>" class="icon-edit" title="edit"></a>					
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
	<div class="span2 text-right ">Total rows: <? echo ($this->pagination->total_rows)?></div>
</div>
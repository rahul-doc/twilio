<script src="<?=asset_url("player/mediaelement-and-player.min.js")?>"></script>
<link href="<?=asset_url("player/mediaelementplayer.min.css")?>" rel="stylesheet" />


<div class="row">
	<div class="span12">
		<?=form_open(admin_url('diary_entries/save'), 'onsubmit="return SendForm(this);"')?>	
			 <div>
				<label>Entry Content: <b class="err err_content"></b></label>
				<textarea class="span12 autogrow" name="content"><?=$item->content?></textarea>
			</div>
			<div>
				<input type="hidden" name="id" value="<?= $item->id?>" />
				<input type="hidden" name="episode_id" value="<?= $item->episode_id?>" />
				<label></label>
				<input type="submit" class="btn btn-primary" value="Save Entry" />
				
			</div>
		<?=form_close()?>
	</div>
</div>


		<h2>Entry Objects</h2>	
		<?if(!$objects):?><div class="no-date">No objects</div><?endif?>
		
		<?foreach($objects as $index=> $object):?>
		<div class="row">
			
			<div class="span6">
				<?=form_open(admin_url('diary_entries/save_transcript'), 'onsubmit="return SendForm(this);"')?>
					<label>Transcription #<?=$index+1?></label>	
					<textarea class="span6 autogrow" name="transcript"><?=$object->transcript?></textarea>
					<input type="hidden" name="id" value="<?=$object->id?>" />
					<input type="hidden" name="entry_id" value="<?=$item->id?>" />
					<input type="submit" class="btn btn-primary" value="Save Transcription" />
				<?=form_close()?>
			</div>

			<div class="span6">
				<div>#<?=$index+1?> <?=ucwords($object->type)?>: <?=anchor($object->uri_prefix.$object->item, NULL, 'target="_blank"')?>
				<!--embed src="< ?=media_url($object->uri_prefix.$object->item)?>" autostart="True" Loop="FALSE" hidden="False"-->
				<!--EMBED SRC="<?=media_url($object->uri_prefix.$object->item)?>" HEIGHT=25 WIDTH=250 TYPE="video/quicktime" PLUGINSPAGE="http://www.apple.com/quicktime/download/" /-->
				</div>

				<?if($object->type=='photo'):?>
					<img src="<?=media_url($object->uri_prefix.$object->item)?>" alt="" />
				<?elseif($object->type=='video'):?>
					<!--video  src="< ?=media_url($object->uri_prefix.$object->item)?>" type="video/flv" 	controls="controls" preload="none"></video-->
					<EMBED SRC="<?=media_url($object->uri_prefix.$object->item)?>" HEIGHT=360 WIDTH=480 TYPE="video/quicktime" PLUGINSPAGE="http://www.apple.com/quicktime/download/" />
				<?elseif($object->type=='audio'):?>
					<EMBED SRC="<?=media_url($object->uri_prefix.$object->item)?>" HEIGHT=40 WIDTH=350 TYPE="video/quicktime" PLUGINSPAGE="http://www.apple.com/quicktime/download/" />
					<!--audio src="< ?=media_url($object->uri_prefix.$object->item)?>" type="audio/wav" controls="controls"></audio-->	
				<?endif?>
			</div>
		</div>	
		<hr>		
		<?endforeach?>
	</div>
</div>

<script>

$('audio,video').mediaelementplayer({
	success: function(player, node) {
		$('#' + node.id + '-mode').html('mode: ' + player.pluginType);
	}
});

</script>

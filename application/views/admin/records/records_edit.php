	
	<?=form_open(admin_url('records/save'), 'onsubmit="return SendForm(this);"')?>
	<div class="row">
		<div class="span6">
			<?=form_open(admin_url('diary_entries/save_transcript'), 'onsubmit="return SendForm(this);"')?>
				<label>Transcription</label>	
				<textarea class="span6 autogrow" name="transcript"><?=$item->transcript?></textarea>
				<input type="hidden" name="id" value="<?=$item->id?>" />
				<input type="hidden" name="entry_id" value="<?=$item->entry_id?>" />
				<input type="submit" class="btn btn-primary" value="Save Transcription" />
			<?=form_close()?>
		</div>

		<div class="span6">
			<div> <?=$item->type?>: <?=anchor($item->url, NULL, 'target="_blank"')?></div>

			<?if($item->type=='photo'):?>
					<img src="<?=media_url($item->uri_prefix.$item->item)?>" alt="" />
				<?elseif($item->type=='video'):?>
					<!--video  src="< ?=media_url($item->uri_prefix.$item->item)?>" type="video/flv" 	controls="controls" preload="none"></video-->
					<EMBED SRC="<?=media_url($item->uri_prefix.$item->item)?>" HEIGHT=360 WIDTH=480 TYPE="video/quicktime" PLUGINSPAGE="http://www.apple.com/quicktime/download/" />
				<?elseif($item->type=='audio'):?>
					<EMBED SRC="<?=media_url($item->uri_prefix.$item->item)?>" HEIGHT=40 WIDTH=350 TYPE="video/quicktime" PLUGINSPAGE="http://www.apple.com/quicktime/download/" />
					<!--audio src="< ?=media_url($object->uri_prefix.$object->item)?>" type="audio/wav" controls="controls"></audio-->	
				<?endif?>
		</div>
	</div>
	<?=form_close()?>



<script src="<?=asset_url("player/mediaelement-and-player.min.js")?>"></script>
<link href="<?=asset_url("player/mediaelementplayer.min.css")?>" rel="stylesheet" />

<script>

$('audio,video').mediaelementplayer({
	success: function(player, node) {
		$('#' + node.id + '-mode').html('mode: ' + player.pluginType);
	}
});

</script>
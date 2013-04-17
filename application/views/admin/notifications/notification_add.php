
<?=form_open(admin_url('notifications/save'))?>
	
	
        
       
        <div style="overflow: hidden;">
            <div class="floatdiv"><label>Doctor <b class="err err_status"></b></label>
		<?=form_dropdown('doctor[]', $docs, 'class="medium"','multiple');?>
            </div>
		 <div class="floatdiv"><label>Patient <b class="err err_status"></b></label>
		<?=form_dropdown('patient[]', $pats, 'class="medium"','multiple');?>
	 </div>
		 <div class="floatdiv"><label>Groups <b class="err err_status"></b></label>
		<?=form_dropdown('groups[]', $grps, 'class="medium"','multiple');?>
                      </div>
	</div>
        <label class="checkbox left"><?=form_checkbox('is_email', 1, '', '');?> Email  </label>
        <label class="checkbox left"><?=form_checkbox('is_sms', 1, '', '');?> SMS  </label>
        <label class="checkboxleft"><?=form_checkbox('is_notification', 1, '', '');?> App Notification  </label>
        <div>
		<label>Message<b class="err err_name"></b></label>
		<textarea class="span12"  name="message" rows="10" ></textarea>
                
	</div>
                
        
       
       
        <div class="center">
		<input type="submit" class="btn btn-primary" value="Send Notification" />
		<a href="<?=admin_url('home')?>">Cancel</a>
	</div>






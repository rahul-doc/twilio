
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

        <div>
		<label>Message<b class="err err_name"></b></label>
		<textarea class="span12" id="wysiwyg" name="message" ></textarea>
                
	</div>
        
   <?$this->load->view('admin/ck_script');?><br/>    
       
        <div class="center">
		<input type="submit" class="btn btn-primary" value="Send Notification" />
		<a href="<?=admin_url('home')?>">Cancel</a>
	</div>






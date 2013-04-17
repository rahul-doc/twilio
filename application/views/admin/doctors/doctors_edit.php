	<div>
		<a href="#NotificationModel" role="button" onclick="get_notification2('chat');">Chat History</a> |
		<a href="#NotificationModel" role="button" onclick="get_notification();">Notification History</a> | 
		<a href="#Notify" role="button" data-toggle="modal">Send Notification</a>
		<br /><br />
	</div>
	
<?=form_open(admin_url('doctors/save'), 'onsubmit="return SendForm(this);"')?>
	
	<div class="span5 pull-right">
		<?foreach(achievments_list() as $type => $achievment_name):?>
		<div class="form-search">
			<input type="button" class="btn btnadd pull-right" data-type="ach[<?=$type?>][]" value="+" />
			<label><?=$achievment_name?>:</label>
			<?if($list = element($type, $achievements)):?>
				<?foreach($list as $ach):?>
					<div>
					<textarea type="text" name="ach[<?=$type?>][u_<?=$ach->id?>]" class="extra_textarea autogrow"><?=$ach->value?></textarea><button class="btn remove" tabindex="-1">X</button>
					</div>
				<?endforeach?>
			<?endif?>
		</div>
		<br>
		<?endforeach?>
	</div>

	<div>
		<label>Is Active <b class="err err_is_active"></b></label>
		<?=form_dropdown('is_active', array('1'=>'Active', '0'=>'Inactive'), $account->is_active)?>
	</div>

	<div>
		<label>Name<b class="err err_name"></b></label>
		<input type="text" class="span4" name="name" value="<?php echo $account->name?>" autofocus="autofocus"/>
	</div>

	<div>
		<label>Email <b class="err err_email"></b></label>
		<input type="text" class="span4" name="email" value="<?php echo $account->email?>"/>
	</div>

	<div>
		<label>Password <b class="err err_password"></b></label>
		<input type="password" class="span4" name="password"/>
		<?if($account->id):?>
		<div class="hint">Leave blank if you don't want to change password</div>
		<?endif?>
	</div>
			
	<div>
		<label>Contact <b class="err err_contact"></b></label>
		<input type="text" class="span4" name="contact" value="<?php echo $item->contact?>"/>
	</div>

	<div>
		<label>Address1 <b class="err err_address1"></b></label>
		<input type="text" class="span4" name="address1" value="<?php echo $item->address1?>"/>
	</div>
	<div>
		<label>Address2 <b class="err err_address2"></b></label>
		<input type="text" class="span4" name="address2" value="<?php echo $item->address2?>"/>
	</div>
	<div>
		<label>Address3 <b class="err err_address3"></b></label>
		<input type="text" class="span4" name="address3" value="<?php echo $item->address3?>"/>
	</div>

	<div>
		<label>City <b class="err err_city"></b></label>
		<input type="text" class="span4" name="city" value="<?php echo $item->city?>"/>
	</div>
	<div>
		<label>State <b class="err err_state"></b></label>
		<input type="text" class="input-mini" name="state" value="<?php echo $item->state?>"/>
	</div>
	
	<div>
		<label>Avatar <b class="err err_avatar_url"></b></label>
		
		<div id="fine_uploader" style="width:200px">
			<noscript>
				<p>Please enable JavaScript to use file uploader.</p>
				<!-- or put a simple form for upload here -->
		    </noscript>
		</div>			
		<div id="file_cnt">
			<?if($account->avatar_url):?>
				<img src="<?=$account->avatar_url?>" alt="" />
			<?endif?>
		</div>
		<input type="text" readonly="readonly" name="avatar_url" id="avatar_url" value="<?=$account->avatar_url?>" />			
	</div>
		
	<div class="center">
		<input type="hidden" name="id" id="id" value="<?php echo isset($account->id)? $account->id : ""?>" />
		<input type="hidden" name="account_group" value="doctor" />
		
		<input type="submit" class="btn btn-primary" value="Save" />
		<a href="<?=admin_url('doctors')?>">Cancel</a>
	</div>

<!-- Modal2 -->
<div id="Notify" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel2">Send Notification</h3>
	</div>
	<div class="modal-body">
		<form id="Notify2" name="Notifyform">
						<table class="table table-striped">

				<tbody>
						
					<tr>
						<td>Type </td>
						<td><label class="checkbox left"><input type="checkbox" name="is_email" id="is_email"value="1"  /> Email</label>
        <label class="checkbox left"><input type="checkbox" name="is_sms" id="is_sms" value="1"  /> SMS</label>
        <label class="checkboxleft"><input type="checkbox" name="is_notification" id="is_notification" value="1"  /> App Notification</label>
						</td>
					
					</tr>
					
					<tr>
						<td>
							Message
						</td>
						<td><input type="text" name="message2" id="message2" class="span3" value="">
						
						</td>
						
					</tr>

					<tr>
						<td>
							&nbsp;
						</td>
						<td>
						<a class="btn btn-primary" id="notbut2" onclick="NotifyNow()">Send Notification</button>
						</td>
						
					</tr>					
				</tbody>
			</table>
		</form>
	</div>
	<div class="modal-footer">
		<!--<button class="btn btn-primary" data-dismiss="modal" id="submit1" onclick="CallSave()">Save</button>-->
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>	

<!-- Modal Notification Model -->
<div id="NotificationModel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Notification History</h3>
	</div>
	<div class="modal-body">
		<span id="notehistory"></span>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<script>

function NotifyNow()
{
	is_email ='';
	is_sms ='';
	is_notification ='';
	
	if($("#is_email").attr("checked"))
		is_email = $("#is_email").val();
	if($("#is_sms").attr("checked"))
		is_sms = $("#is_sms").val();
	if($("#is_notification").attr("checked"))
		is_notification = $("#is_notification").val();
		
	message2 = $("#message2").val();
	id = $("#id").val();	
	contact = $("#contact").val();
	email = $("#email").val();
	//alert(is_email);
	//alert(is_sms);
	//alert(is_notification);
	//alert("message : " + message2);
	//alert('hi');
	
	if(id == '')
	{
		alert("ID is required");
		return false;
	}
	
	if(message2 == '')
	{
		alert('please type message');
	}else
	{
	
		$.get('<?php echo base_url();?>index.php/admin/smscall/email_sms_notify', { is_email: is_email, is_sms: is_sms, is_notification: is_notification, message2: message2, id: id, contact: contact, email: email, notify: 0 })
		.done(function(data) {
		  alert( data);
		});	
	
	}
	return false;
}

	function get_notification(type)
	{
		//allEnterDetailscode=$("#allEnterDetailscode").val();
		//$("#notehistory").html(data);
		//$('#NotificationModel').modal('show');
		id = $("#id").val();
		
		$.ajax({
		 	url:"<?php echo base_url();?>index.php/admin/smscall/view_notification",
		 	type: "GET",
		 	data:{id:id, notify:0, type: type},
		 	 success: function (data) {
		 	 	//alert(data);
		 	 	$("#notehistory").html(data);
		 	 	 $('#NotificationModel').css('width', 'auto');
		 	 	$('#NotificationModel').modal('show');
		 	 }
		 });
	}

</script>
	
<?=form_open()?>

<?$this->load->view('admin/avatar_script')?>

<script type="text/javascript">

	$(function(){
		$('.remove').live('click', function(){
			//if(confirm('Are you sure')){
				$(this).parent().remove();
			//}
		});

		$('.btnadd').on('click', function(){
			
			var $input = $('<textarea>', {	
								type: 'text',
								name: $(this).attr('data-type'),
								'class': 'extra_textarea autogrow'
							}
						);
			var $button = $('<button>', {'class':"btn remove", text:"X"});
			var $div = $('<div>').append($input, $button);

			$(this).parent().append($div);
			
		})
	});

</script>
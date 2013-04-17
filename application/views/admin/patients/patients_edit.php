	<div>
		<a href="#NotificationModel" role="button" onclick="get_notification2('chat');">Chat History</a> |
		<a href="#NotificationModel" role="button" onclick="get_notification('call');">Call History</a> |
		<a href="#NotificationModel" role="button" onclick="get_notification();">Notification History</a> | 
		<a href="#Notify" role="button" data-toggle="modal">Send Notification</a>
		<br /><br />
	</div>
	
<?=form_open(admin_url('patients/save'), 'onsubmit="return SendForm(this);"')?>
	
	<div class="pull-right">
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
		<input type="text" class="span4" name="email" id="email" value="<?php echo $account->email?>"/>
	</div>
	
	<div>
		<label>Password <b class="err err_password"></b></label>
		<input type="password" class="span4" name="password"/>
		<?if($account->id):?>
		<div class="hint">Leave blank if you don't want to change password</div>
		<?endif?>
	</div>

	<div>
		<label>Gender <b class="err err_gender"></b></label>
		<?=form_dropdown('gender', array(''=>'-Gender-', 'Male'=>'Male', 'Female'=>'Female'), $item->gender)?>
	</div>

		
	<div>
		<label>Contact <b class="err err_contact"></b></label>
		<input type="text" class="span4" name="contact" id="contact" value="<?php echo $item->contact?>"/>
		
		<a href="#Callnow" role="button" class="btn" data-toggle="modal"><img border="0" src="<?php echo base_url(); ?>assets/img/call-now.png" width="50" height="40"></a>
	</div>
	
	<div>
		<label>Date of Birth (yyyy-mm-dd	) <b class="err err_dob"></b></label>
		<input type="text" class="span4" data-mask="9999-99-99" name="dob" value="<?php echo $item->dob ? date('Y-m-d', strtotime($item->dob)) : ''?>"/>
	</div>

	<div>
		<label>Devices <b class="err err_dev"></b></label>
		<table border="0">
		<tr>
		<td>
			<input type="hidden" name="assigned_hidden" value ="unchanged" id="assigned_hidden">
			<label>Assigned <b class="err err_dev"></b></label>
			<select id="assigned_devices" name="assigned_devices" size="10" multiple="multiple">
			<?php foreach($assigned_devices as $key => $assigned_dev){
				
			echo '<option value="'.$assigned_dev->device_ID.'">'.$assigned_dev->name.'</option>';
			}?>
			</select>
		</td>
		<td align="center" valign="middle">
			<input type="button" value="--&gt;"
			 onclick="moveOptions(this.form.assigned_devices, this.form.available_devices);" /><br />
			<input type="button" value="&lt;--"
			 onclick="moveOptions(this.form.available_devices, this.form.assigned_devices);" />
		</td>
		<td>
			<label>Available <b class="err err_dev"></b></label>
			<select id="available_devices" name="available_devices" size="10" multiple="multiple">
			<?php foreach($available_devices as $key => $available_dev){
			echo '<option value="'.$available_dev->id.'">'.$available_dev->name.'</option>';
			}?>

			</select>
		</td>
	</tr>
</table> 
	</div>

	<div>
		<label>Allergy <b class="err err_allergy"></b></label>
		<textarea class="span6 autogrow" name="allergy"><?php echo $item->allergy?></textarea>
	</div>

	<div>
		<label>Past History <b class="err err_past_history"></b></label>
		<textarea class="span6 autogrow" name="past_history"><?php echo $item->past_history?></textarea>
	</div>

	<div>
		<label>Insurance <b class="err err_insurance"></b></label>
		<textarea class="span6 autogrow" name="insurance"><?php echo $item->insurance?></textarea>
	</div>

	<div>
		<label>Corporate Plans <b class="err err_corporate_plans"></b></label>
		<textarea class="span6 autogrow" name="corporate_plans"><?php echo $item->corporate_plans?></textarea>
	</div>
	<div>

		<label>Discount Schemes <b class="err err_discount_schemes"></b></label>
		<textarea class="span6 autogrow" name="discount_schemes"><?php echo $item->discount_schemes?></textarea>
	</div>


	<div>
		<input type="hidden" name="id" id="id" value="<?php echo isset($account->id)? $account->id : ""?>" />
		<input type="hidden" name="account_group" value="patient" />
		<label></label>
		<input type="submit" class="btn btn-primary" value="Save" />
	</div>

<!-- Modal1 -->
<div id="Callnow" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Call Now</h3>
	</div>
	<div class="modal-body">
		<form id="Callnow2" name="Callnowform">
						<table class="table table-striped">

				<tbody>
								
					<tr>
						<td>
							Comments
						</td>
						<td><input type="text" name="comments" id="comments" class="span3" value="">
						
						</td>
						
					</tr>

					<tr>
						<td>
							&nbsp;
						</td>
						<td>
						<a class="btn btn-primary" id="callbut" onclick="CallNow()">Call</button>
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
function CallNow()
{
	contact = $("#contact").val();
	comments = $("#comments").val();
	id = $("#id").val();

	//alert(contact);
	//alert('hi');
	
	if(contact != '')
	{
		alert('calling ' + contact);
		
		$.get('<?php echo base_url();?>index.php/admin/smscall/call', { contact: contact, comments: comments, id: id, notify: 1 })
		.done(function(data) {
		  alert( data);
		});
		
	}
	
	//break;
	return false;
}

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
	
		$.get('<?php echo base_url();?>index.php/admin/smscall/email_sms_notify', { is_email: is_email, is_sms: is_sms, is_notification: is_notification, message2: message2, id: id, contact: contact, email: email, notify: 1 })
		.done(function(data) {
		  alert( data);
		});	
	
	}
	return false;
}

function CallSave()
{
	comments = $("#comments").val();
	alert(comments);
	
	$.get('<?php echo base_url();?>index.php/admin/smscall/save', { comments: comments })
	.done(function(data) {
	  alert( data);
	});
		
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
		 	data:{id:id, notify:1, type: type},
		 	 success: function (data) {
		 	 	//alert(data);
		 	 	$("#notehistory").html(data);
		 	 	 $('#NotificationModel').css('width', 'auto');
		 	 	$('#NotificationModel').modal('show');
		 	 }
		 });
	}
</script>

<script language="JavaScript" type="text/javascript">
<!--

var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);

function addOption(theSel, theText, theValue)
{
  var newOpt = new Option(theText, theValue);
  var selLength = theSel.length;
  theSel.options[selLength] = newOpt;
}

function deleteOption(theSel, theIndex)
{ 
  var selLength = theSel.length;
  if(selLength>0)
  {
    theSel.options[theIndex] = null;
  }
}

function moveOptions(theSelFrom, theSelTo)
{
  
  var selLength = theSelFrom.length;
  var selectedText = new Array();
  var selectedValues = new Array();
  var selectedCount = 0;
  
  var i;
  
  // Find the selected Options in reverse order
  // and delete them from the 'from' Select.
  for(i=selLength-1; i>=0; i--)
  {
    if(theSelFrom.options[i].selected)
    {
      selectedText[selectedCount] = theSelFrom.options[i].text;
      selectedValues[selectedCount] = theSelFrom.options[i].value;
      deleteOption(theSelFrom, i);
      selectedCount++;
    }
  }
  
  // Add the selected text/values in reverse order.
  // This will add the Options to the 'to' Select
  // in the same order as they were in the 'from' Select.
  for(i=selectedCount-1; i>=0; i--)
  {
    addOption(theSelTo, selectedText[i], selectedValues[i]);
  }
  placeInHidden('-', 'assigned_devices', 'assigned_hidden');
  if(NS4) history.go(0);
}

function setSubmitDebugOuput()
{
  document.getElementById('divOutput').innerHTML = window.location.search;
}

function placeInHidden(delim, selStr, hidStr)
{
  var selObj = document.getElementById(selStr);
  var hideObj = document.getElementById(hidStr);
  hideObj.value = '';
  for (var i=0; i<selObj.options.length; i++) {
    hideObj.value = hideObj.value ==
      '' ? selObj.options[i].value : hideObj.value + delim + selObj.options[i].value;
  }
}
//-->
</script>
	
	
<?=form_open()?>

<?$this->load->view('admin/avatar_script')?>
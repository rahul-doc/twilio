<?php
class Smscall extends CI_Controller {

	function index( )
	{
		echo 'hi';
	}
	
	function sms()
	{
		$this->load->library('twilioservice');
		$this->twilioservice->SendSMS('+919820397227',"Hello monkey!");
		print "ok";
		
	}
	
	function call()
	{	
		$this->load->helper('url');
	
		$url = '/test/callmessage';
		#echo site_url($url);exit;
		$url = site_url($url);
		
		$contact = $this->input->get("contact");
		$comments = $this->input->get("comments");
		$id = $this->input->get("id");
		$notify = $this->input->get("notify");
		
		$this->load->database();

		$data = array('datec' => date("Y-m-d H:i:s", time()),
				   'type' => 'call' ,
				   'noti_type' => $notify ,
				   'noti_ref' => $id,
				   'noti_msg' => $comments
				);
		
		#print_r($data);
		$this->db->insert('notification', $data);
			
		$this->load->library('twilioservice');
		#$this->twilioservice->CallTo('+919820397227',$url);
		$this->twilioservice->CallTo($contact,$url);
		#print "ok";
	}
	
	function callmessage()
	{
		$this->load->helper('my-twilio'); // references library/my-twilio-helper.php

		$response = new Services_Twilio_Twiml();
		$response->say('Hello, you will now be connected with the caller');
		#$response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

		echo $response;
	}
	
	function notify()
	{
		
		$id = $this->input->get("id");
		$message = $this->input->get("message");
		$type = $this->input->get("type");
		//doctor = 0, patient =1
		
		#print "hello {$id}/{$message}/{$type} <br>";
		$this->load->library('twilioservice');

		#$response = $this->twilioservice->c2($id, $message, $type)
		$response = $this->twilioservice->Notify($id, $message, $type);
		print $response;		
	}
	
	function email()
	{	
		/*
			require_once(APPPATH . 'libraries/ses.php');
			$ses = new SimpleEmailService(ses_Access_Key, ses_Secret_Key);
			
			$m = new SimpleEmailServiceMessage();
			$m->setFrom('swardi2001@hotmail.com');
			$m->addTo($to);
			$m->setSubject('Notification');
			$m->setMessageFromString($message);
			$m->setSubjectCharset('ISO-8859-1');
			$m->setMessageCharset('ISO-8859-1');
			
			$result=$ses->sendEmail($m);
			print_r($result);
		*/
		
		$to = $this->input->get("to");
		$message = $this->input->get("message");
		#$type = $this->input->get("type");
		//doctor = 0, patient =1
		
		#print "hello {$id}/{$message}/{$type} <br>";
		$this->load->library('twilioservice');

		#$response = $this->twilioservice->c2($id, $message, $type)
		$response = $this->twilioservice->Email($to, $message);
		print $response;		
	}	
	
	function email_sms_notify()
	{
		$is_email = $this->input->get("is_email");
		$is_sms = $this->input->get("is_sms");
		$is_notification = $this->input->get("is_notification");
		$message2 = $this->input->get("message2");
		$id = $this->input->get("id");
		$contact = $this->input->get("contact");
		$email = $this->input->get("email");
		$notify = $this->input->get("notify");

		#print "is_email: $is_email, is_sms: $is_sms, is_notification: $is_notification, message2: $message2, id: $id, contact: $contact, email: $email, notify: $notify <hr>\n";
		//exit;
		$this->load->library('twilioservice');

		$this->load->database();

		if($is_email == '1')
		{
			$data = array('datec' => date("Y-m-d H:i:s", time()),
					   'type' => 'email' ,
					   'noti_type' => $notify ,
					   'noti_ref' => $id,
					   'noti_msg' => $message2
					);
			
			#print_r($data);
			$this->db->insert('notification', $data);

			$response = $this->twilioservice->Email($email, $message2);	
		}
		if($is_sms == '1')
		{
			$data = array('datec' => date("Y-m-d H:i:s", time()),
					   'type' => 'sms' ,
					   'noti_type' => $notify ,
					   'noti_ref' => $id,
					   'noti_msg' => $message2
					);
			$this->db->insert('notification', $data);
			
			$this->twilioservice->SendSMS($contact,$message2);
		}
		if($is_notification == '1')
		{
			$data = array('datec' => date("Y-m-d H:i:s", time()),
					   'type' => 'notification' ,
					   'noti_type' => $notify ,
					   'noti_ref' => $id,
					   'noti_msg' => $message2
					);
			$this->db->insert('notification', $data);
			
			$response = $this->twilioservice->Notify($id, $message2, $type);
		}		
	}
	
	function view_notification()
	{
		$id = $this->input->get("id");
		$notify = $this->input->get("notify");	
		$type = $this->input->get("type");	

		$this->load->database();

		$this->db->select('*');
		$this->db->from('notification');
		$this->db->where('noti_type', $notify);
		$this->db->where('noti_ref', $id);
		if($type)
		{
			$this->db->where('type', $type);
		}
		
		$this->db->order_by('datec desc');

		$query = $this->db->get();
	
		#echo $this->db->last_query();
		
		#print_r($data);
		#$this->db->insert('notification', $data);
			
		#print "id = $id , notify = $notify <hr>\n";
		
		$table = "<table border=1 cellspacing=2 cellpadding=2>\n";
		$table .= "<tr><th>Date</th><th>Message Type</th><th>Message</th></tr>\n";
		
		foreach ($query->result() as $row)
		{
			$datec = $row->datec;
			$type = $row->type;
			$noti_msg = $row->noti_msg;
			
			$table .= "<tr><td>$datec</td><td>$type</td><td>$noti_msg</td></tr>\n";

			#echo $row->datec;
		}
		$table .= "</table>\n";
		print $table;
		
	}
}


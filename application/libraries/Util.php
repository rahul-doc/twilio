<?php

class Util
{

	function send_email($to_email, $message, $subject = "Test email", $from_email ="hello@example.com", $from_name="SiteName")
	{
		$CI = & get_instance();
		$CI->load->library('email');

		$CI->email->from($from_email,$from_name);
	   	$CI->email->to($to_email);
		$CI->email->subject($subject);
		$CI->email->message($message);
		$CI->email->set_newline("\r\n"); //do not remove

		return	$CI->email->send();

	}

	function send_tpl_email($content_view, $data)
	{
		
		$to_email = element('email', $data, conf('contact_email')); //use contact_email if not indicated
		$subject = element('subject', $data, 'no subject');
		$from_email = element('from_email', $data, conf('contact_email')); //if not indicated, use default contact_email
		$from_name = element('from_name', $data, conf('site_name')); //if not indicated use site name
		
		$data['content_view'] = $content_view;

		$CI = & get_instance();
		$message = $CI->load->view('email/email_layout', $data, TRUE);

		
		return $this->send_email($to_email, $message, $subject, $from_email, $from_name);
	}

	function log_action($message, $module)
	{
		return; //disable it
		$CI = & get_instance();
		
		$user = $CI->session->userdata('user');
		if($user){
			$log['ip'] = $CI->session->userdata('ip_address');
			$log['user_id'] = $user->id;
			$log['user_type'] = $CI->session->userdata('logged') =='admin';
			$log['user_role'] = $user->role_code;
			$log['module'] = str_replace("_model", "", $module);
			$log['action'] = $message;
			
			$CI->db->insert('user_logs', $log);
		}
		

	}


}
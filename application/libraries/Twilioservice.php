<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twilioservice
{
	public function SendSMS($to, $message)
	{
		
		$this->api = & get_instance();
		$this->api->load->helper('my-twilio');
		
		// Request the service object
		$service = get_twilio_service();

		// make a sms
		try {

			$message = $service->account->sms_messages->create(
			  '+16464026957', // From a valid Twilio number
			  $to, // Text this number
			  $message
			);

			echo "done";
			#print $message->sid;					
			
			#print $call->sid;
		}
		catch (Exception $e) 
		{
			print $e->getMessage();
			// handle any error conditions here
		}		
	}		
	
	public function CallTo($to, $url)
	{
		
		$this->api = & get_instance();
		$this->api->load->helper('my-twilio');
		
		// Request the service object
		$service = get_twilio_service();

		// make a call
		try {
			$call = $service->account->calls->create(
						'+16464026957', // from this number
						$to, // to this number
						$url // callback url
					);
			echo "done";
			#print $call->sid;
		}
		catch (Exception $e) 
		{
			print $e->getMessage();
			// handle any error conditions here
		}		
	}	

	public function Notify($id, $message, $type)
	{
		#print "hello {$id}/{$message}/{$type} <br>";

			$url="https://api.my-doc.com/notification/notify/{$id}/{$message}/{$type}";
		
			#print $url;
			//cURL Request For SMS notification

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"X-client-identifier:{$_SERVER['REMOTE_ADDR']}",
				"X-client-platform:Server",
				"X-client-version:1.00",
				"X-client-type:Admin",
				"Accept: application/json",
			)); 
			#$data['item']['response'][$i]['res']=curl_exec($ch);
			$response = curl_exec($ch);
			#print_r($response);
			curl_close($ch);	
			return $response;		


	
	}		
	
	public function Email($to, $message)
	{
		#require_once('ses.php');
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
		return $result;
		
		#print "$to, $message <hr>\n";
		#print_r($result);
	}
}

/* End of file twilioservice.php */
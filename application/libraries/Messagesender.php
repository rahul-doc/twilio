<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('ses.php');

class Messagesender
{

	public function SendSMS(){
		return;
	}
	
	public function SendEmail($to, $from, $subject, $message_txt, $message_html){
		
		$ses = new SimpleEmailService(ses_Access_Key, ses_Secret_Key);
		$m = new SimpleEmailServiceMessage();
		$m->addTo($to);
		$m->setFrom($from);
		$m->setSubject($subject);
		$m->setMessageFromString($message_txt, $message_html);
		$m->setSubjectCharset('ISO-8859-1');
		$m->setMessageCharset('ISO-8859-1');
		$result=$ses->sendEmail($m);		
		if($result)
			return true;
		else
			return false;	
		
	}
	
	public function SendNotification($id,$message,$type){
            $url="https://api.my-doc.com/notification/notify/{$id}/{$message}/{$type}";
                    
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
            if(curl_exec($ch)){
                curl_close($ch);
                return true;
            }
            else
                return false;
            
	}
	
	


}

/* End of file MessageSender.php */
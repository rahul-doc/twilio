<?php
class Test extends CI_Controller {

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
		
		$this->load->library('twilioservice');
		$this->twilioservice->CallTo('+919820397227',$url);
		print "ok";
	}
	
	function callmessage()
	{
		$this->load->helper('my-twilio'); // references library/my-twilio-helper.php

		$response = new Services_Twilio_Twiml();
		$response->say('Hello, you will now be connected with the caller');
		#$response->play('https://api.twilio.com/cowbell.mp3', array("loop" => 5));

		echo $response;
	}
}


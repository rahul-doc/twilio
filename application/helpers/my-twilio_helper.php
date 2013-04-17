<?php

if (!class_exists('Services_Twilio')) {
	/**
	 * The main Twilio.php file contains an autoload method for its dependent
	 * classes, we only need to include the one file manually.
	 */
	include_once(APPPATH.'libraries/Services/Twilio.php');
}

/**
 * Return a twilio services object.
 *
 * Since we don't want to create multiple connection objects we
 * will return the same object during a single page load
 *
 * @return object Services_Twilio
 */
function get_twilio_service() {
	static $twilio_service;

	if (!($twilio_service instanceof Services_Twilio)) {
		/**
		 * This assumes that you've defined your SID & TOKEN as constants
		 * Replace with a way to get your SID & TOKEN if different
		 */
		 $TWILIO_SID = 'AC82e8976a526448027c3719d3bc151aba';
		 $TWILIO_TOKEN = 'fe7ac3689fb4ba4a427f9aeb719506c5';
		 
		 $twilio_service = new Services_Twilio($TWILIO_SID, $TWILIO_TOKEN);
	}

	return $twilio_service;
}

?>
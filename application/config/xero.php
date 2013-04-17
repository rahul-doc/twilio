<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['invoice'] = array(
			'path' 				=> '', // REQUIRED, temp pdf generated will save it here => recommend outside the public_html for security purposes
			'log' 				=> true, // store pdf invoice file on server, false: pdf generated will be removed post processing
			'send_email'		=> true, // send pdf invoice to recipient, sandbox: false (buyer email is fake email) OR
			'sandbox_recipient'	=> '', // send pdf invoice to test email account if send=true (for testing purpose) => enter temp email for testing purposes
		);

$config['xero_key'] = 'XFVUEZ4XPGMUVVKVPIBODN6VEIF4OU';
$config['xero_secret'] = 'YWVMEETV6PO7XFTNPZ0XZ9G0TTMXHU';
$config['xero_public_cert_path'] = ''; // => REQUIRED
$config['xero_private_key_path'] = ''; // => REQUIRED

?>
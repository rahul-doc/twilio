<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['ipn'] = array(
			'log' => true, // save text file on server for logging purpose (OPTIONAL)
			'path'	=> '' // path to save the log file (OPTIONAL, REQUIRED if log=true) => with separator  trailings
		);
		
// SANDBOX - MyDoc			  
$config['Sandbox'] = true;
$config['Version'] = '95.0';
$config['EndPointURL'] = 'https://api-3t.sandbox.paypal.com/nvp';
$config['Username'] = 'seller_test_api1.mydoc.com';
$config['Password'] = '1364370720';
$config['Signature'] = 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AcYr2QQWrOrtgF8lt-mfrpHqLhsT';
$config['ReturnURL'] = ''; // REQUIRED
$config['CancelURL'] = ''; // REQUIRED
$config['IpnEndPointURL'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$config['SellerPayPalEmail'] = 'seller_test@mydoc.com';
//$config['SSL'] = $_SERVER['SERVER_PORT'] == '443' ? true : false;

// LIVE			  
/*$config['Sandbox'] = false;
$config['Version'] = '95.0';
$config['EndPointURL'] = 'https://api-3t.paypal.com/nvp';
$config['Username'] = '';
$config['Password'] = '';
$config['Signature'] = '';
$config['ReturnURL'] = '';
$config['CancelURL'] = '';
$config['IpnEndPointURL'] = 'https://www.paypal.com/cgi-bin/webscr';
$config['SellerPayPalEmail'] = '';
//$config['SSL'] = $_SERVER['SERVER_PORT'] == '443' ? true : false;*/
?>
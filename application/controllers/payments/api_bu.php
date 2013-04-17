<?php
require(APPPATH.'libraries/REST_Controller.php');  

class Api extends REST_Controller {
	
	var $currency;
	var $ipn_config;
	var $paypal_config;
	var $invoice_config;
	var $email_config;
	
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');

		$this->config->load('payments');
		$this->config->load('paypal', true);
		$this->config->load('xero');
		$this->config->load('email', true);
		
		$this->currency = $this->config->item('currency');
		$this->ipn_config = $this->config->item('ipn');
		$this->invoice_config = $this->config->item('invoice');
		$this->email_config = $this->config->item('email');
		$this->paypal_config = $this->config->item('paypal');
	}
	
	private function CURLRequest($Request)
	{
		$auth = 'USER=' . $this->paypal_config['Username'] . '&PWD=' . $this->paypal_config['Password'] . '&SIGNATURE=' . $this->paypal_config['Signature'] . '&version=' . $this->paypal_config['Version'];
		
		$header[] = 'Accept:application/json';
		$header[] = 'Accept-Language: en_US';

		$curl = curl_init();
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_URL, $this->paypal_config['EndPointURL']);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $auth . $Request);
				
		/*if($this->APIMode == 'Certificate')
		{
			curl_setopt($curl, CURLOPT_SSLCERT, $this->PathToCertKeyPEM);
		}*/
		
		$Response = curl_exec($curl);		
		curl_close($curl);
		return $Response;	
	}
	  
	function recurring_post()
	{
		$Response = array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>'');

		// post data
		$price_id = isset($_POST['price_id']) ? intval($_POST['price_id']) : 0;
		if(empty($price_id))
			$Response['Error'][] = 'Invalid price ID';
			 
		$acc_id = isset($_POST['acc_id']) ? intval($_POST['acc_id']) : 0; 
		if(empty($acc_id))
			$Response['Error'][] = 'Invalid payer account';

		$qty = isset($_POST['qty']) ? floatval($_POST['qty']) : 0; 
		if(empty($qty))
			$Response['Error'][] = 'Invalid qty';
		
		if(!empty($Response['Error']))
		{
			$this->response($Response,412); // Precondition Failed	
			return;
		}
		
		// price list
		$this->load->model('price_list_model', 'price_list');
		$price_list_obj = $this->price_list->get_record($price_id, false);
		
		// product/service not found
		if(empty($price_list_obj->price_id))
		{
			$Response['Error'][] = 'Product/service not found';
			$this->response($Response,412); // Precondition Failed	
			return;
		}
		
		// amount
		$amount = number_format($qty * floatval($price_list_obj->unit_price),2);
		
		// API Request
		$Request = '&METHOD=BMCreateButton';
		$Request .= '&BUTTONCODE=ENCRYPTED';
		$Request .= '&BUTTONTYPE=SUBSCRIBE';
		$Request .= '&BUTTONSUBTYPE=SERVICES';
		
		$Request .= '&L_BUTTONVAR0=custom='. $acc_id . '_' . date('Ymd_His');
		$Request .= '&L_BUTTONVAR1=currency_code='. $this->currency;
		
		$Request .= '&L_BUTTONVAR2=no_shipping=1';
		$Request .= '&L_BUTTONVAR3=no_note=1';

		$Request .= '&L_BUTTONVAR4=item_name=' . $price_list_obj->description;
		$Request .= '&L_BUTTONVAR5=item_number=' . $price_list_obj->price_id;

		$Request .= '&L_BUTTONVAR6=quantity='. number_format($qty,2);

		$Request .= '&L_BUTTONVAR7=a3='. $amount;
		$Request .= '&L_BUTTONVAR8=p3=1';
		$Request .= '&L_BUTTONVAR9=t3=M';
		
		$Request .= '&L_BUTTONVAR10=src=1';
		$Request .= '&L_BUTTONVAR11=sra=1';
		
		parse_str($this->CURLRequest($Request),$Response);

		if($Response['ACK'] == 'Success')
		{
			$Response = array('Success'=>1,'Button'=>$Response['WEBSITECODE'],'Msg'=>'','Error'=>'');
			$this->response($Response,200);
			return;
		} else {
			$Response = array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>$Response['L_LONGMESSAGE0']);
			$this->response($Response,200);
			return;
		}
		
	}

	function topup_post()
	{
		$Response = array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>'');

		// post data
		$acc_id = isset($_POST['acc_id']) ? intval($_POST['acc_id']) : 0; 
		if(empty($acc_id))
			$Response['Error'][] = 'Invalid payer account';

		$amount = isset($_POST['amount']) ? number_format(floatval($_POST['amount']),2) : 0; 
		
		$min_topup = floatval($this->config->item('min_topup'));

		if(empty($amount) || floatval($amount) < $min_topup)
			$Response['Error'][] = 'Minimum topup ' . $this->currency . number_format($min_topup,2);

		if(!empty($Response['Error']))
		{
			$this->response($Response,412); // Precondition Failed	
			return;
		}
		
		// API Request
		$Request = '&METHOD=BMCreateButton';
		$Request .= '&BUTTONCODE=ENCRYPTED';
		$Request .= '&BUTTONTYPE=BUYNOW';
		$Request .= '&BUTTONSUBTYPE=SERVICES';

		$Request .= '&L_BUTTONVAR0=custom='. $acc_id . '_' . date('Ymd_His');
		$Request .= '&L_BUTTONVAR1=currency_code='. $this->currency;
		
		$Request .= '&L_BUTTONVAR2=no_shipping=1';
		$Request .= '&L_BUTTONVAR3=no_note=1';

		$Request .= '&L_BUTTONVAR4=item_number=TOPUP';
		$Request .= '&L_BUTTONVAR5=item_name=TOPUP' . $amount;
		
		$Request .= '&L_BUTTONVAR6=amount=' . $amount;
		
		parse_str($this->CURLRequest($Request),$Response);

		if($Response['ACK'] == 'Success')
		{
			$Response = array('Success'=>1,'Button'=>$Response['WEBSITECODE'],'Msg'=>'','Error'=>'');
			$this->response($Response,200);
			return;
		} else {
			$Response = array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>$Response['L_LONGMESSAGE0']);
			$this->response($Response,200);
			return;
		}
	}
	
	function payment_post()  
	{   
		// default
		$Response = array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>'');
		
		// input
		$input_price_id = isset($_POST['price_id']) ?  intval($_POST['price_id']) : 0;
		if(empty($input_price_id))
			$Response['Error'][] = 'Invalid product/service';
			
		$input_qty = isset($_POST['qty']) ?  floatval($_POST['qty']) : 0;
		if(empty($input_qty))
			$Response['Error'][] = 'Invalid qty';

		$input_acc_id = isset($_POST['acc_id']) ?  intval($_POST['acc_id']) : 0;
		if(empty($input_acc_id))
			$Response['Error'][] = 'Invalid payer account';

		$input_patient = isset($_POST['patient']) ? $_POST['patient'] : '' ;
		if(empty($input_patient))
			$Response['Error'][] = 'Invalid payer account';

		$input_check_balance = isset($_POST['check_balance']) ? $_POST['check_balance'] : 0 ;
		
		// validation failed
		if(!empty($Response['Error']))
		{
			$this->response($Response,412); // Precondition Failed 
			return;
		}

		// get price info
		$this->load->model('price_list_model', 'price_list');
		$price_list_obj = $this->price_list->get_record($input_price_id, false);

		// price exist ?
		if(empty($price_list_obj->price_id))
		{
			// return failed
			$Response['Error'][] = 'Product/service not available'; 
			$this->response($Response,412); // Precondition Failed 
			return; 
		}

		// purchase amount = qty x unit price
		$amount = $input_qty * floatval($price_list_obj->unit_price);

		// check balance
		if(!empty($input_check_balance))
		{
			$balance = $this->balance_post($input_acc_id);
			if($balance !== false && floatval($balance) < floatval($amount))
			{
				$Response['Error'][] = 'Balance not sufficient'; 
				$this->response($Response,412); // Precondition Failed 
				return; 
			}
		}

		// product type
		$product_type = $price_list_obj->product_type == 'other' ? $price_list_obj->product_type_other : $price_list_obj->product_type;
		
		// actual amount + commission
		$commission_factor = floatval($this->config->item('commission'));
		$commission = $amount * $commission_factor;
		$actual_amount = $amount - $commission;
		
		// payment data
		$payment_data = array(
			'acc_id' 		=> $price_list_obj->acc_id,
			'amount' 		=> $amount,
			'commission' 	=> $commission,
			'type' 			=> $product_type,
			'from_acc_id' 	=> $input_acc_id,
			'txn_time' 		=> strtotime(date('m/d/Y H:i:s')),
			'status' 		=> 'Completed'
		);

		$this->load->model('transactions_model', 'transaction');
		$result = $this->transaction->insert_record($payment_data);

		if(!empty($result))
		{
			// update balance for non recurring
			if(empty($price_list_obj->recurring))
			{
				$this->load->model('accounts_model', 'account');
				// update balance - patient
				$before_obj = $this->account->get_balance($input_acc_id);
				if(!empty($before_obj))
					$this->account->update_balance($input_acc_id, floatval($before_obj->balance) - floatval($amount));
					
				// update balance - doctor/provider
				$before_obj = $this->account->get_balance($price_list_obj->acc_id);
				if(!empty($before_obj))
					$this->account->update_balance($price_list_obj->acc_id, floatval($before_obj->balance) + floatval($actual_amount));
			}
			
			$Response = array('Success'=>1,'Button'=>'','Msg'=>'Payment created','Error'=>'');
			$this->response($Response,200);
			return;
		} else {
			$Response['Error'][] = 'Failed, payment not created'; 
			$this->response($Response,200);
			return; 
		}
	}  
	 
	function balance_post($acc_id)
	{
		$this->load->model('accounts_model', 'account');
		$account_obj = $this->account->get_balance($acc_id);
		if(!empty($account_obj))
			return $account_obj->balance;
		else
			return false;	
	}
	
	function xinvoice_post()
	{
		$args = $_POST['args'];
		$request = json_decode($args,true);
		
		require_once('includes/xero.php');
		$xero = new Xero(XERO_KEY, XERO_SECRET,PUBLIC_CERT_PATH,PRIVATE_KEY_PATH,'json');
		$invoice_result = $xero->Invoices( $request );
		$this->response($invoice_result,200);
	}
	
	function xpayment_post()
	{
		$args = $_POST['args'];
		$request = json_decode($args,true);
		
		require_once('includes/xero.php');
		$xero = new Xero(XERO_KEY, XERO_SECRET,PUBLIC_CERT_PATH,PRIVATE_KEY_PATH,'json');
		$payment_result = $xero->Payments( $request );
		$this->response($payment_result,true,200);
	}
	
	function xpdfinvoice_post()
	{
		$this->load->helper('email');

		$invoice_id = $_POST['invoice_id'];
		$invoice_number = $_POST['invoice_number'];
		if($this->paypal_config['Sandbox'])
		{
			$recipient_email = $this->invoice_config['sandbox_recipient'];
		} else {
			$recipient_email = $_POST['recipient_email'];
		}
		
		if (!valid_email($recipient_email))
		{
			$this->response(array('Success'=>0,'Error'=>'Invalid email address'),true,200);	
			return;
		}
		
		require_once('includes/xero.php');
		$xero = new Xero(XERO_KEY, XERO_SECRET,PUBLIC_CERT_PATH,PRIVATE_KEY_PATH,'json');
		$pdf_invoice = $xero->Invoices($invoice_id, '', '', '', 'pdf');	

		$path = $this->invoice_config['path'] . $invoice_number . '.pdf';
		$fh  = fopen($path, 'w+');
		fwrite($fh, $pdf_invoice);
		rewind($fh);
		fclose($fh);
		
		$this->load->library('email', $this->email_config);  
		$this->email->from($this->email_config['smtp_user'], 'My-Doc.com');
		$this->email->to($recipient_email);
		$this->email->subject($invoice_number);
		$this->email->message('Please see attached invoice.'); 
		$this->email->attach($path);
		$this->email->send();
		
		if(!$this->invoice_config['log'])
			unlink($path);
		
		//mail('admin@wisenetware.com', 'Attachment' , $this->email->print_debugger());
		
		$this->response(array('Success'=>1),true,200);
	}
	
}
?>
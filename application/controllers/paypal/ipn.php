<?php
class Ipn extends CI_Controller {

	public function index()
	{
		// LOAD CONFIG
		$this->config->load('payments');
		$this->config->load('paypal', true);
		$this->config->load('xero');
		
		$paypal_config = $this->config->item('paypal');
		$ipn_config = $paypal_config['ipn'];
		$invoice_config = $this->config->item('invoice');

		// LOAD MODELS	
		$this->load->model('accounts_model', 'account');
		$this->load->model('transactions_model', 'transaction');
		$this->load->model('price_list_model', 'price_list');

		// LISTENER - STEP 1 =================================================================================
		$raw_post_data = file_get_contents('php://input');
		parse_str($raw_post_data,$myPost);
		
		// initial validation - check if payment send to valid paypal email account (avoid hacked by fake ipn) 	
		if(empty($myPost['receiver_email']) || strtolower(trim($myPost['receiver_email'])) !== strtolower(trim($paypal_config['SellerPayPalEmail']))) return;
		
		// log ipn as file
		if($ipn_config['log'] && is_dir($ipn_config['path']))
		{
			$file = $ipn_config['path'] . date('Ymd_His_') . uniqid() . '.txt';
			$current = print_r($myPost, true);
			file_put_contents($file, $current);		
		}
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate&' . $raw_post_data;
		
		// LISTENER - STEP 2 (IPN verification) =================================================================
		$ch = curl_init($paypal_config['IpnEndPointURL']);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // to check the existence of a common name and also verify that it matches the hostname provided
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		if( !($res = curl_exec($ch)) ) {
			// error_log
			curl_close($ch);
			return;
		}
		curl_close($ch);

		if (strcmp ($res, "VERIFIED") !== 0) return;		

		// LISTENER - STEP 3 (process verified ipn) =================================================================

		// initial validation
		if(trim($_POST['payment_status']) !== 'Completed') return; // process completed payment only
		if(empty($_POST['txn_id']) || $_POST['txn_id'] !== $myPost['txn_id']) return;
		if(empty($_POST['txn_type']) || $_POST['txn_type'] !== $myPost['txn_type']) return;
		if(empty($_POST['receiver_email']) || strtolower(trim($_POST['receiver_email'])) !== strtolower(trim($paypal_config['SellerPayPalEmail']))) return;
		if(empty($_POST['mc_currency']) || strtolower(trim($_POST['mc_currency'])) !== trim(strtolower($this->config->item('currency')))) return;
		if(empty($_POST['mc_gross']) || strtolower(trim($_POST['mc_gross'])) !== trim(strtolower($myPost['mc_gross']))) return;
		
		// validate value
		$amount = isset($_POST['mc_gross']) ? floatval($_POST['mc_gross']) : 0;
		if(empty($amount)) return;
		$custom = isset($_POST['custom']) ? explode("_" , $_POST['custom']) : '';
		$acc_id = intval($custom[0]);
		if(empty($acc_id)) return;
		// check duplicate txn_id ?
		$result = $this->transaction->get_record(array('paypal_txn_id'=>$_POST['txn_id']),false);
		if(!empty($result->id)) return;
		// check payapl transaction type						
		switch(trim($_POST['txn_type']))
		{
			case 'web_accept': // topup =================================================
				if(trim($_POST['item_number']) !== 'TOPUP') return; // process topup only
				// payment data
				$payment_data = array(
					'acc_id' 		=> $acc_id,
					'amount' 		=> $amount,
					'commission' 	=> 0,
					'type' 			=> 'TOPUP',
					'from_acc_id' 	=> $acc_id,
					'txn_time' 		=> strtotime(date('m/d/Y H:i:s')),
					'status' 		=> 'Completed',
					'paypal_txn_id' => $_POST['txn_id'],
					'paypal_txn_type'	=> $_POST['txn_type']
				);
				
				// insert transaction
				$new_trans_id = $this->transaction->insert_record($payment_data);
				if(empty($new_trans_id)) return;
				
				// update balance
				$before_obj = $this->account->get_balance($acc_id);
				if(!empty($before_obj))
					$this->account->update_balance($acc_id, floatval($before_obj->balance) + floatval($amount));
				
				// XERO INVOICE
				$patient_info = $this->account->get_record($acc_id,false);
				if(empty($patient_info->id)) return;
				
				// generate invoice request
				$request = array(
						array(
							"Type"=>"ACCREC",
							"Reference"=>$new_trans_id . ' - ' . $_POST['txn_id'],
							"CurrencyCode"=>$_POST['mc_currency'],
							"Contact" => array(
								"Name" => $patient_info->name,
								"EmailAddress" => $patient_info->email
							),
							"Date" => date('Y-m-d'),
							"DueDate" => date('Y-m-d'),
							"Status" => "AUTHORISED",
							"SentToContact" => true,
							"LineAmountTypes" => "Exclusive",
							"LineItems"=> array(
								"LineItem" => array(
									"Description" => "TOPUP",
									"Quantity" => "1",
									"UnitAmount" => number_format($amount,2),
									"AccountCode" => "200" // sales
								)
							)
						)
					);					
				// set params
				$params = new stdClass();
				$params->api_endpoint = base_url() . 'index.php/payments/api/xinvoice/';
				$params->api_userpwd = 'admin:p1234';
				$params->api_header[] = 'Accept:application/json'; 
				$params->api_header[] = 'X-API-KEY:keyAPI1234%';
				$params->api_postfields = array(
							'args' => json_encode($request)
						);  
				// call invoice api		
				$ch_response = $this->PaymentAPI_CURLRequest($params); 
				$inv_result = json_decode($ch_response['response'],true);

				//mail('admin@wisenetware.com', 'Invoice' , print_r($inv_result, true));

				if($inv_result["Status"] !== "OK") return;
				// xero invoice created
				$new_invoice_id = $inv_result["Invoices"]["Invoice"]["InvoiceID"];
				$new_invoice_number = $inv_result["Invoices"]["Invoice"]["InvoiceNumber"];
				// update transaction - invoice id / number
				$this->transaction->update_record(array('xero_inv_id'=>$new_invoice_id,'xero_inv_number'=>$new_invoice_number) , $new_trans_id);
				// XERO PAYMENT
				// generate payment request
				$request = array(
						array(
							"Invoice" => array(
								"InvoiceNumber" => $new_invoice_number
							),
							"Account" => array(
								"Code" => "600"
							),
							"Date" => date('Y-m-d'),
							"Amount"=>number_format($amount,2)
						)
					);					
				// update params
				$params->api_endpoint = base_url() . 'index.php/payments/api/xpayment/';
				$params->api_postfields = array(
							'args' => json_encode($request)
						); 
				// call payment api
				$ch_response = $this->PaymentAPI_CURLRequest($params); 
				$payment_result = json_decode($ch_response['response'],true);

				//mail('admin@wisenetware.com', 'Payment' , print_r($payment_result, true));

				// XERO INVOICE PDF
				if(!$invoice_config['send_email'] || !is_dir($invoice_config['path'])) return;
				
				// update params
				$params->api_endpoint = base_url() . 'index.php/payments/api/xpdfinvoice/';
				$params->api_postfields = array(
						'invoice_id' => $new_invoice_id,
						'invoice_number' => $new_invoice_number,
						'recipient_email' => $patient_info->email
					);  
				$ch_response = $this->PaymentAPI_CURLRequest($params);
				break;
					
			case 'subscr_payment': // recurring ====================================================
				// price list
				$price_list_obj = $this->price_list->get_record(intval($_POST['item_number']), false);

				if(empty($price_list_obj->price_id)) return;
				if(empty($price_list_obj->recurring_monthly)) return;

				// actual amount + commission
				$qty = $amount / $price_list_obj->unit_price;
				$commission_factor = $this->config->item('commission');
				$commission = $amount * floatval($commission_factor);
				$actual_amount = $amount - $commission;

				// payment data
				$payment_data = array(
					'acc_id' 		=> $price_list_obj->acc_id,
					'amount' 		=> $actual_amount,
					'commission' 	=> $commission,
					'type' 			=> $price_list_obj->product_type == 'other' ? $price_list_obj->product_type_other : $price_list_obj->product_type,
					'from_acc_id' 	=> $acc_id,
					'txn_time' 		=> strtotime(date('m/d/Y H:i:s')),
					'status' 		=> 'Completed',
					'paypal_txn_id' => $_POST['txn_id'],
					'paypal_txn_type'	=> $_POST['txn_type']
				);
				
				// insert transaction
				$new_trans_id = $this->transaction->insert_record($payment_data);
				if(!empty($new_trans_id))
				{
					// update balance - deduct patient
					$before_obj = $this->account->get_balance($acc_id);
					if(!empty($before_obj))
						$this->account->update_balance($acc_id, floatval($before_obj->balance) - floatval($amount));
					// update balance - add to doctor
					$before_obj = $this->account->get_balance($price_list_obj->acc_id);
					if(!empty($before_obj))
						$this->account->update_balance($price_list_obj->acc_id, floatval($before_obj->balance) + floatval($actual_amount));
				}
				
				// XERO INVOICE
				$patient_info = $this->account->get_record($acc_id,false);
				if(empty($patient_info->id)) return;
				
				// generate invoice request
				$request = array(
						array(
							"Type"=>"ACCREC",
							"Reference"=>$new_trans_id . ' - ' . $_POST['txn_id'],
							"CurrencyCode"=>$_POST['mc_currency'],
							"Contact" => array(
								"Name" => $patient_info->name,
								"EmailAddress" => $patient_info->email
							),
							"Date" => date('Y-m-d'),
							"DueDate" => date('Y-m-d'),
							"Status" => "AUTHORISED",
							"SentToContact" => true,
							"LineAmountTypes" => "Exclusive",
							"LineItems"=> array(
								"LineItem" => array(
									array(
										"Description" => $price_list_obj->description,
										"Quantity" => number_format($qty,2),
										"UnitAmount" => number_format($price_list_obj->unit_price * (1- floatval($commission_factor)),2),
										"AccountCode" => "200" // sales
									),
									array(
										"Description" => "COMMISSION",
										"Quantity" => number_format($qty,2),
										"UnitAmount" => number_format($commission/$qty,2),
										"AccountCode" => "200" // sales
									)
								),
							)
						)
					);	
									
				// set params
				$params = new stdClass();
				$params->api_endpoint = base_url() . 'index.php/payments/api/xinvoice/';
				$params->api_userpwd = 'admin:p1234';
				$params->api_header[] = 'Accept:application/json'; 
				$params->api_header[] = 'X-API-KEY:keyAPI1234%';
				$params->api_postfields = array(
							'args' => json_encode($request)
						);  
				// call invoice api		
				$ch_response = $this->PaymentAPI_CURLRequest($params); 
				$inv_result = json_decode($ch_response['response'],true);

				mail('admin@wisenetware.com', 'Invoice' , print_r($inv_result, true));

				if($inv_result["Status"] !== "OK") return;
				// xero invoice created
				$new_invoice_id = $inv_result["Invoices"]["Invoice"]["InvoiceID"];
				$new_invoice_number = $inv_result["Invoices"]["Invoice"]["InvoiceNumber"];
				// update transaction - invoice id / number
				$this->transaction->update_record(array('xero_inv_id'=>$new_invoice_id,'xero_inv_number'=>$new_invoice_number) , $new_trans_id);
				// XERO PAYMENT
				// generate payment request
				$request = array(
						array(
							"Invoice" => array(
								"InvoiceNumber" => $new_invoice_number
							),
							"Account" => array(
								"Code" => "600"
							),
							"Date" => date('Y-m-d'),
							"Amount"=>number_format($amount,2)
						)
					);					
				// update params
				$params->api_endpoint = base_url() . 'index.php/payments/api/xpayment/';
				$params->api_postfields = array(
							'args' => json_encode($request)
						); 
				// call payment api
				$ch_response = $this->PaymentAPI_CURLRequest($params); 
				$payment_result = json_decode($ch_response['response'],true);

				mail('admin@wisenetware.com', 'Payment' , print_r($payment_result, true));

				// XERO INVOICE PDF
				if(!$invoice_config['send_email'] || !is_dir($invoice_config['path'])) return;
				
				// update params
				$params->api_endpoint = base_url() . 'index.php/payments/api/xpdfinvoice/';
				$params->api_postfields = array(
						'invoice_id' => $new_invoice_id,
						'invoice_number' => $new_invoice_number,
						'recipient_email' => $patient_info->email
					);  
				$ch_response = $this->PaymentAPI_CURLRequest($params);
				break;	
		}
	}

	private function PaymentAPI_CURLRequest($params)
	{
		$ch = curl_init($params->api_endpoint);  
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $params->api_userpwd);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $params->api_header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params->api_postfields);  
		$ch_response = curl_exec($ch);
		$ch_httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		return array('httpcode'=>$ch_httpcode,'response'=>$ch_response);
	}
	
	function thank_you()
	{
		$this->load->view('paypal/thank_you');
	}

	function cancelled()
	{
		$this->load->view('paypal/cancelled');
	}

}
?>
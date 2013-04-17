<?php
class Ipn extends CI_Controller {

	public function index()
	{
		// config
		$this->config->load('payments');
		$paypal_config = $this->config->item('PayPalConfig');

		// listener - step 1
		$raw_post_data = file_get_contents('php://input');
		parse_str($raw_post_data,$myPost);
		
		// initial validation	
		if(strtolower(trim($myPost['receiver_email'])) !== strtolower(trim($paypal_config['SellerPayPalEmail']))) exit();
		
		// log ipn
		if($this->config->item('ipn_log'))
		{
			$file = $this->config->item('ipn_log_path') . microtime() . '.txt';
			$current = file_get_contents($file);
			$current .= print_r($myPost, true);
			file_put_contents($file, $current);		
		}
		// end log ipn

		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}		
		
		// ipn verification - step 2
		$ch = curl_init($paypal_config['IpnEndPointURL']);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit();
		}
		curl_close($ch);		

		// validation result - step 3
		if (strcmp ($res, "VERIFIED") == 0) 
		{
			// validation
			if(trim($_POST['payment_status']) !== 'Completed') exit();
			// check duplicate txn_id ?
			if(strtolower(trim($_POST['receiver_email'])) !== strtolower(trim($paypal_config['SellerPayPalEmail']))) exit();
			if(strtolower(trim($_POST['mc_currency'])) !== trim(strtolower($this->config->item('currency')))) exit();

			$amount = isset($_POST['mc_gross']) ? floatval($_POST['mc_gross']) : 0;
			if(empty($amount)) exit();
			$custom = isset($_POST['custom']) ? explode("_" , $_POST['custom']) : '';
			$acc_id = intval($custom[0]);
			if(empty($acc_id)) exit();

			// models		
			$this->load->model('price_list_model', 'price_list');
			$this->load->model('transactions_model', 'transaction');
			$this->load->model('accounts_model', 'account');
			
			switch(trim($_POST['txn_type']))
			{
				case 'web_accept': // topup
					if(trim($_POST['item_number']) !== 'TOPUP') exit(); // process topup only
					// payment data
					$payment_data = array(
						'acc_id' 		=> $acc_id,
						'amount' 		=> $amount,
						'commission' 	=> 0,
						'type' 			=> 'TOPUP',
						'from_acc_id' 	=> $acc_id,
						'txn_time' 		=> strtotime(date('m/d/Y H:i:s')),
						'status' 		=> 'Completed'
					);
					
					$result = $this->transaction->insert_record($payment_data);
					if(!empty($result))
					{
						// update balance
						$before_obj = $this->account->get_balance($acc_id);
						if(!empty($before_obj))
							$this->account->update_balance($acc_id, floatval($before_obj->balance) + floatval($amount));
					}
					//
					break;	
				case 'subscr_payment': // recurring
					// price list
					$price_list_obj = $this->price_list->get_record(intval($_POST['item_number']), true);
					
					if(empty($price_list_obj)) exit();
					if(empty($price_list_obj->recurring_monthly)) exit();
					
					// actual amount + commission
					$commission_factor = $this->config->item('commission');
					$commission = $amount * $commission_factor;
					$actual_amount = $amount - $commission;

					// payment data
					$payment_data = array(
						'acc_id' 		=> $price_list_obj->acc_id,
						'amount' 		=> $actual_amount,
						'commission' 	=> $commission,
						'type' 			=> $price_list_obj->product_type == 'other' ? $price_list_obj->product_type_other : $price_list_obj->product_type,
						'from_acc_id' 	=> $acc_id,
						'txn_time' 		=> strtotime(date('m/d/Y H:i:s')),
						'status' 		=> 'Completed'
					);
					
					$result = $this->transaction->insert_record($payment_data);
					if(!empty($result))
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
					//
					break;	
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
		}

		//$ipn_data = parse_url($_REQUEST);
	}
	
	public function thank_you()
	{
		$this->load->view('paypal/thank_you');
	}

	public function cancelled()
	{
		$this->load->view('paypal/cancelled');
	}

}
?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions extends Admin_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->model('transactions_model', 'model');
		$this->_set_filter(array('status', 'name'));	
		
	}

	public function index($offset = 0)
	{ 	
		$data['title'] = "Transactions list";
		$data['items'] = $this->_get_list($offset);
		$this->render("admin/transactions/transactions_view", $data);
	}

	private function _get_list($offset=0){
     
		$count = $this->model->get_count($this->filter);
		$limit = element('per_page', $this->filter, LIMIT);

		/* pagination */
		$config['base_url'] = admin_url("transactions/index/");
		$config['uri_segment'] = 4;
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		$this->load->library('pagination');
		$this->pagination->initialize($config);

        return $this->model->get_items($this->filter, $offset, $limit);
	}

	
	function get_ajax_list()
	{
		
        $d['items'] = $this->_get_list();
		$data['list'] = $this->load->view('admin/transactions/transactions_list', $d, TRUE);
		echo json_encode($data);
	}
	
	// FOR DEMO PURPOSES
	function demo()
	{
		$data['title'] = "Transactions - Demo";
		$this->load->model('price_list_model', 'price_list');
		$data['product'] = $this->price_list->get_options('price_list', 'price_id', 'description', '', '', array('is_active'=>1), 'Description');

		// submission
		if(isset($_POST['submit']) && $_POST['submit'] == "Process")
		{
			$process_response = array('success'=>0,'button'=>'','msg'=>'','error'=>'');
			
			// check input
			$price_id = isset($_POST['product']) ? intval($_POST['product']) : 0;
			if(empty($price_id))
				$process_response['error'][] = 'Invalid product/service';

			$qty = isset($_POST['qty']) ? floatval($_POST['qty']) : 0;
			if(empty($qty))
				$process_response['error'][] = 'Invalid qty';

			$acc_id = isset($_POST['acc_id']) ? intval($_POST['acc_id']) : 0;
			if(empty($acc_id))
				$process_response['error'][] = 'Invalid payer account ID';
			
			$patient = isset($_POST['patient']) ? $_POST['patient'] : '';
			if(empty($patient))
				$process_response['error'][] = 'Invalid payer account ID';

			$check_balance = isset($_POST['check_balance']) ? 1 : 0;

			// validation failed
			if(!empty($process_response['error']))
			{
				$data['response'] = $process_response;
				$this->render("admin/transactions/transactions_demo", $data);
				return;
			}
			
			// check recurring		
			$data['selected_product'] = $price_id;
			$data['recurring_product'] = false;
			
			// price list
			$product_obj = $this->price_list->get_record($price_id, true);
			if(empty($product_obj))
			{
				$process_response['Error'][] = 'Product/service not found';
				$data['response'] = $process_response;
				$this->render("admin/transactions/transactions_demo", $data);
				return;
			}
			
			// payment
			if(!empty($product_obj) && !empty($product_obj->recurring_monthly))
			{
				$data['recurring_product'] = true;
				
				// recurring - generate button via API
				$API_ENDPOINT = base_url() . 'index.php/payments/api/recurring/';
				$API_KEY = 'keyAPI1234%';
				$API_USERPWD = 'admin:p1234';
				$API_POSTFIELDS = array(
							'price_id' => $price_id,
							'acc_id' => $acc_id,
							'qty' => $qty,
						);  
		
				$header[] = 'Accept:application/json';
				$header[] = 'X-API-KEY:' . $API_KEY;
			
				$ch = curl_init($API_ENDPOINT);  
				curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $API_USERPWD);  
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_POST, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $API_POSTFIELDS);  
		  
				$ch_response = curl_exec($ch);
				curl_close($ch); 
				
				$result = json_decode($ch_response,true);
				
				$data['response'] = $result;
				$this->render("admin/transactions/transactions_demo", $data);
				return;
			} else {
				// non recurring - create payment
				$API_ENDPOINT = base_url() . 'index.php/payments/api/payment/';
				$API_KEY = 'keyAPI1234%';
				$API_USERPWD = 'admin:p1234';
				$API_POSTFIELDS = array(
							'price_id' => $price_id,
							'qty' => $qty,
							'acc_id' => $acc_id,
							'patient' => $patient,
							'check_balance' => $check_balance
						);  
			
				$header[] = 'Accept:application/json';
				$header[] = 'X-API-KEY:' . $API_KEY;
				
				$ch = curl_init($API_ENDPOINT);  
				curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, $API_USERPWD);  
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				curl_setopt($ch, CURLOPT_POST, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $API_POSTFIELDS);  
		  
				$ch_response = curl_exec($ch);
				curl_close($ch); 

				$result = json_decode($ch_response);				
				$data['response'] = $result;
				$this->render("admin/transactions/transactions_demo", $data);
				return;
			}
		} else {
			$this->render("admin/transactions/transactions_demo", $data);
			return;
		}
	}
	
	public function autocomplete($input)
	{
		$input = urldecode(trim($input));
		if(empty($input) || strlen($input) < 3) return;
		
		$this->db->select(array('p.id','p.acc_id','name','email'));
		$this->db->where("name LIKE '" . $input . "%'");		
        $this->db->from('patient_profile'. " p")
        			->join('accounts a', 'a.id=p.acc_id');
		$this->db->order_by("name","asc");		
		$query = $this->db->get();
		$result = $query->result(); 
		foreach($result as $v)
		{
			echo '<li id="doc_' . $v->acc_id . '"><span class="sp_name">' . $v->name . '</span>&nbsp;<span class="sp_email">' . $v->email . '</span></li>';	
		}
	}
	// END OF DEMO
	
	
	// FOR DEMO TOPUP PURPOSES
	function topup()
	{
		// title
		$data['title'] = "Transactions - Demo Top Up";
		
		// submission
		if(isset($_POST['submit']) && $_POST['submit'] == "Process")
		{
			$process_response = array('response'=>array('Success'=>0,'Button'=>'','Msg'=>'','Error'=>''), 'con_status'=>'412');
	
			// check input
			if( (!isset($_POST['acc_id']) || empty($_POST['acc_id'])) || (!isset($_POST['patient']) || empty($_POST['patient'])) )
				$process_response['response']['Error'][] = 'Please select patient';
			if( !isset($_POST['currency']) || empty($_POST['currency']) )
				$process_response['response']['Error'][] = 'Currency required';
			
			$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

			if( empty($amount) || $amount < floatval($this->config->item('min_topup')) )
				$process_response['response']['Error'][] = 'Minimum topup ' . $this->config->item('currency') . $this->config->item('min_topup');
			
			// validation failed
			if(!empty($process_response['response']['Error']))
			{
				$data['response'] = $process_response;
				$this->render("admin/transactions/transactions_topup", $data);
				return;
			}
			
			// generate button via API
			$API_ENDPOINT = base_url() . 'index.php/payments/api/topup/';
			$API_KEY = 'keyAPI1234%';
			$API_USERPWD = 'admin:p1234';
			$API_POSTFIELDS = array(
						'acc_id' => isset($_POST['acc_id']) ? intval($_POST['acc_id']) : 0,
						'patient' => isset($_POST['patient'])? $_POST['patient'] : '',
						'amount' => isset($_POST['amount']) ? number_format($amount,2) : 0,
					);  
	
			$header[] = 'Accept:application/json';
			$header[] = 'X-API-KEY:' . $API_KEY;
		
			$ch = curl_init($API_ENDPOINT);  
			curl_setopt($ch, CURLOPT_FORBID_REUSE, 1); 
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $API_USERPWD);  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			//curl_setopt($ch, CURLOPT_HEADER, 1); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $API_POSTFIELDS);  
	  
			$ch_response = curl_exec($ch);
			$process_response['con_status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch); 
			
			$result = json_decode($ch_response, true);

			$data['response'] = $result;
			$this->render("admin/transactions/transactions_topup", $data);
			return;
		}
		$this->render("admin/transactions/transactions_topup", $data);
	}
	// END OF DEMO TOPUP
	

}



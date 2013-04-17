<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('payments_model', 'model');
		$this->_set_filter(array('amount'));
	}

	public function index($offset = 0)
	{
		$data['title'] = "Payments";
        $data['items'] = $this->_get_list($offset);
		$this->render('admin/payments/payment_view', $data);
	}

	private function _get_list($offset)
	{
       	$limit = 15;
		$count = $this->model->get_count($this->filter);
		$this->_pagination($count, $limit);
        return $this->model->get_items($this->filter, $offset, $limit);
	}

	public function _pagination($count, $limit)
	{
		$config['base_url'] = admin_url("payments/index/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
		$config['num_links'] =10;
		$this->load->library('pagination');
        $this->pagination->initialize($config);
	}

	function get_ajax_list()
	{
        $d['items'] = $this->_get_list(0);
		$data['list'] = $this->load->view('admin/payments/payment_list', $d, TRUE);
		echo json_encode($data);
	}

	function add()
	{
    	$this->edit(0);
	}

	function edit($id)
	{
		$data['title'] = $id ? "Edit Payment" : "Add Payment";

		$data['item'] = $this->model->get_record($id, FALSE);
		$this->render('admin/payments/payment_edit', $data);
	}

	function paypal()
	{
		$data['title'] = 'Payment - PayPal';
		$this->render('admin/payments/payment_paypal', $data);	
	}

	function pay_pp()
	{
		
		$data['title'] = 'Payment - PayPal Approval';
		$Response = $this->paypal->createPayment($_POST['request']);
		if($Response['status'] == 201)
		{
			foreach($Response['response']->links as $v)
			{
				if($v->rel == 'approval_url')
				{
					$data['apv_url'] = $v->href;
					redirect($data['apv_url'], 'location', 301);
					exit();
				}
			}
			$this->render('admin/payments/payment_apv', $data);	
		}
	}

	function pp_approved()
	{
		$this->index();
	}
	 	
	function save()	
	{	
		$id = $this->input->post('payment_id');

		//check validation
		if($this->model->get_from_post()){
			//save account
			$new_id = $this->model->save($id);
			if($new_id){
				$data = $this->model->get_results();
			}
		} else{
			$data = $this->model->get_results();
		}
		if(isset($data['success'])){
			set_success($data['success']);
			$data['redirect'] = admin_url('payments');
		}
		echo json_encode($data);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Price_List extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->config->load('payments');
		$this->load->model('price_list_model', 'model');
		$this->_set_filter(array('provider'));

	}

	public function index($offset = 0)
	{
		$data['title'] = "Master Price List";
        $data['items'] = $this->_get_list($offset);
		$this->render('admin/price_list/price_view', $data);
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
		$config['base_url'] = admin_url("price_list/index/");
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
		$data['list'] = $this->load->view('admin/price_list/price_list', $d, TRUE);
		echo json_encode($data);
	}

	function add()
	{
    	$this->edit(0);
	}

	function edit($id)
	{
		$data['title'] = $id ? "Edit Price" : "Add Price";
		$data['item'] = $this->model->get_record($id, FALSE);
		$this->render('admin/price_list/price_edit', $data);
	}

	public function autocomplete($input)
	{
		$input = urldecode(trim($input));
		if(empty($input) || strlen($input) < 3) return;
		
		$this->db->select(array('d.id','d.acc_id','name','email'));
		$this->db->where("name LIKE '" . $input . "%'");		
        $this->db->from('doctor_profile'. " d")
        			->join('accounts a', 'a.id=d.acc_id');
		$this->db->order_by("name","asc");		
		$query = $this->db->get();
		$result = $query->result(); 
		foreach($result as $v)
		{
			echo '<li id="doc_' . $v->acc_id . '"><span class="sp_name">' . $v->name . '</span>&nbsp;<span class="sp_email">' . $v->email . '</span></li>';	
		}
	}
	 	
	function save()	
	{	
		$id = $this->input->post('price_id');

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
			$data['redirect'] = admin_url('price_list');
		}
		echo json_encode($data);
	}

	function delete()
	{
    	$id = $this->input->post('price_id');		
		$this->model->delete_record($id);
		$data = $this->model->get_results();	
		echo json_encode($data);
	}

}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devices extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('devices_model', 'model');
		$this->load->model('device_patient_model', 'dev_patient');
		$this->load->library('Twonet_partner',array("endpoint"=>$this->config->item('twonet_endpoint'),"key"=>$this->config->item('twonet_key'),"secret"=>$this->config->item('twonet_secret')));
		$this->_set_filter(array('status', 'name'));

	}

	
	
	public function index($offset = 0)
	{
		$data['title'] = "Devices";
        $data['items'] = $this->_get_list($offset);

       	$this->render('admin/devices/devices_view', $data);
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
		$config['base_url'] = admin_url("devices/index/");
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
		$data['list'] = $this->load->view('admin/devices/devices_list', $d, TRUE);
		echo json_encode($data);
	}



	function add()
	{
    	$this->edit(0);
	}

	function edit($id)
	{
		
		$data['title'] = $id ? "Edit Device" : "Add Device";
		//$data['account'] = $this->account->get_record($id);
		$data['item'] = $this->model->get_record($id, FALSE);
		$this->render('admin/devices/devices_edit', $data);
	}

	function save(){
    	$id = $this->input->post('id');
       	
    	$this->model->save($id);
		$data = $this->model->get_results();
		if(isset($data['success'])){
			set_success($data['success']);
			$data['redirect'] = admin_url('devices');;
		}
		echo json_encode($data);
	}

	function activate()
	{
		$id = $this->input->post('id');		
		echo $this->model->activate($id, 'is_active');
	}

	function delete()
	{
    	$id = $this->input->post('id');		
		$this->model->delete_record($id);
		$data = $this->model->get_results();	
		echo json_encode($data);
	}

}
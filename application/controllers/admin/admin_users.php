<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_users extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin_users_model', 'model');
		$this->_set_filter(array('status'));

	}

	public function index($offset = 0)
	{
		$data['title'] = "Administrators";
        $data['items'] = $this->_get_list($offset);
       	$this->render('admin/admin_users/admin_users_view', $data);
	}

	private function _get_list($offset){

       	$limit = 15;
		$count = $this->model->get_count($this->filter);
		$this->_pagination($count, $limit);
        return $this->model->get_users($this->filter, $offset,$limit);
	}


	public function _pagination($count, $limit){
		$config['base_url'] = site_url("admin/admin_users/index/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
		$config['num_links'] =10;
		$this->load->library('pagination');
        $this->pagination->initialize($config);
	}

	function get_ajax_list(){
		
        $d['items'] = $this->_get_list(0);

		$data['list'] = $this->load->view('admin/admin_users/admin_users_list', $d, TRUE);
		echo json_encode($data);
	}



	function add(){
    	$this->edit(0);
	}

	function edit($id){
		$data['title'] = $id ? "Edit admin user" : "Add admin user";
		$data['item'] = $this->model->get_record($id);
		$data['roles'] = $this->model->get_options('a_roles', 'code', 'Name', 'super', 'All rights');
		$this->render('admin/admin_users/admin_users_edit', $data);
	}

	function save(){
    	$id = $this->input->post('id');
       	$this->model->save($id);
		$data = $this->model->get_results();
		if(isset($data['success'])){
			set_success($data['success']);
			$data['redirect'] = site_url('admin/admin_users');
		}
		echo json_encode($data);
	}

	function activate(){
		$id = $this->input->post('id');
		$this->_protect_superadmin($id);
		echo $this->model->activate($id);
	}

	function delete(){
    	$id = $this->input->post('id');
		$this->_protect_superadmin($id);
		$this->model->delete_record($id);
		$data = $this->model->get_results();	
		echo json_encode($data);
	}

	function _protect_superadmin($user_id){
		if($user_id ==1){
			$data['error'] = "You are not able to perform this action with superadmin account";
			die(json_encode($data));
		}
	}


}



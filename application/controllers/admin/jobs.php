<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs extends Admin_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->model('jobs_model', 'model');
		$this->_set_filter(array('status', 'name'));	
		
	}

	public function index($offset = 0)
	{ 	
		$data['title'] = "List"; // "Jobs";
		$data['items'] = $this->_get_list($offset);
		$this->render("admin/jobs/jobs_view", $data);
	}

	private function _get_list($offset=0){
     
		$count = $this->model->get_count($this->filter);
		$limit = element('per_page', $this->filter, LIMIT);

		/* pagination */
		$config['base_url'] = admin_url("jobs/index/");
		$config['uri_segment'] = 4;
		$config['total_rows'] = $count;
		$config['per_page'] = $limit;
		$this->load->library('pagination');
		$this->pagination->initialize($config);

        return $this->model->get_items($this->filter, $offset, $limit);
	}

	
	public function get_ajax_list()
	{
		
        $d['items'] = $this->_get_list();
		$data['list'] = $this->load->view('admin/jobs/jobs_list', $d, TRUE);
		echo json_encode($data);
	}

	public function add()
	{
		$this->edit(0);
	}

	public function edit($id)
	{		
		$data['title'] = $id ?  "Edit" : "Add"; //"Edit Job" : "Add Job";
		$data['item'] = $this->model->get_record($id);
    	$this->render('admin/jobs/jobs_edit', $data);
	}

	public function save()
	{
    	$id = $this->input->post('id');
      	$this->model->save($id);
		$data = $this->model->get_results();
		if(isset($data['success'])){
		
			set_success('Operation successfully');
			$data['redirect'] = site_url('admin/jobs');
		}		
		echo json_encode($data);
	}

	
	public function activate()
	{
		$id = $this->input->post('id');
		echo $this->model->activate($id, 'ca01disabled');
	}
	


	public	function delete()
	{
    	$id = $this->input->post('id');
    	if($this->model->activate($id, 'ca01deleted')){
    		$data['success'] = 'Deleted successfully';
    	}
    	else{
    		$data = $this->model->get_results();	
    	}
		
		echo json_encode($data);
	}


}



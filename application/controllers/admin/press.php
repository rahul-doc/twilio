<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Press extends Admin_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->model('press_model', 'model');
		$this->_set_filter(array('status', 'name'));	
		
	}

	public function index($offset = 0)
	{ 	
		$data['title'] = "List"; // "Press Releases";
		$data['items'] = $this->_get_list($offset);
		$this->render("admin/press/press_view", $data);
	}

	private function _get_list($offset=0){
     
		$count = $this->model->get_count($this->filter);
		$limit = element('per_page', $this->filter, LIMIT);

		/* pagination */
		$config['base_url'] = admin_url("press/index/");
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
		$data['list'] = $this->load->view('admin/press/press_list', $d, TRUE);
		echo json_encode($data);
	}

	public function add()
	{
		$this->edit(0);
	}

	public function edit($id)
	{		
		$data['title'] = $id ?  "Edit" : "Add"; //"Edit Press Release" : "Add Press Release";
		$data['item'] = $this->model->get_record($id);
    	$this->render('admin/press/press_edit', $data);
	}

	public function save()
	{
    	$id = $this->input->post('id');
      	$this->model->save($id);
		$data = $this->model->get_results();
		if(isset($data['success'])){
		
			set_success('Operation successfully');
			$data['redirect'] = site_url('admin/press');
		}		
		echo json_encode($data);
	}

	
	public function activate(){
		$id = $this->input->post('id');
		echo $this->model->activate($id, 'pr01disabled');
	}
	


	public	function delete(){
    	$id = $this->input->post('id');
    	if($this->model->activate($id, 'pr01deleted')){
    		$data['success'] = 'Deleted successfully';
    	}
    	else{
    		$data = $this->model->get_results();	
    	}

		//$this->model->delete_record($id);
		
		echo json_encode($data);
	}

	public function upload()
	{
		$this->load->helper('qqupload');

		
		$allowedExtensions = array("pdf");

		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		//set max size to 10 MB if server allow
		$upload_mb = min($max_upload, $max_post, $memory_limit, 10);
		$sizeLimit = $upload_mb*1000*1000; 

		
		$result = qqupload( PDF, $allowedExtensions, $sizeLimit);

	   	if(isset($result['success']))
		{
			$file = $result['file'];	
		}

	   	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}



}



<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Records extends Admin_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->model('records_model', 'model');
		$this->_set_filter(array('status', 'name'));	
		
	}

	public function index($offset = 0)
	{ 	
		$data['title'] = "List of records"; 
		$data['items'] = $this->_get_list($offset);
		$this->render("admin/records/records_view", $data);
	}

	private function _get_list($offset=0){
     
		$count = $this->model->get_count($this->filter);
		$limit = element('per_page', $this->filter, LIMIT);

		/* pagination */
		$config['base_url'] = admin_url("records/index/");
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
		$data['list'] = $this->load->view('admin/records/records_list', $d, TRUE);
		echo json_encode($data);
	}

	public function add()
	{
		$this->edit(0);
	}

	public function edit($id)
	{		
		$data['title'] = $id ?  "Edit" : "Add"; 
		$data['item'] = $this->model->get_record($id);
    	$this->render('admin/records/records_edit', $data);
	}

	public function save()
	{
		$this->load->model('diary_entries_model');

    	$obj_id = $this->input->post('id');
		$entry_id = $this->input->post('entry_id');
		$transcript = $this->input->post('transcript');

		$this->diary_entries_model->save_transcription($obj_id, $transcript, $entry_id);
		echo json_encode(array('success'=>'Saved'));
	}

}



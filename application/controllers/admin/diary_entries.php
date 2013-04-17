<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diary_entries extends Admin_Controller {

	public $filter = null;
	public $count = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('diary_entries_model', 'model');
		$this->_set_filter(array('status'));
	}

	public function index($episode_id, $offset = 0)
	{
		$this->load->model('accounts_model', 'accounts');
		$data['title'] = "Episode Entries";
		$data['episode_id'] = $episode_id;
		$data['patient'] = $this->accounts->get_user_by_episode($episode_id);
		$data['items'] = $this->_get_list($offset, $episode_id);
		$data['count'] = $this->count;
       	$this->render('admin/diary_entries/diary_entries_view', $data);
	}

	private function _get_list($offset, $episode_id)
	{
       	$limit = 15;
		$this->count = $this->model->get_count($this->filter);
		$this->_pagination($this->count, $limit, $episode_id);
        return $this->model->get_items($episode_id, $this->filter, $offset,$limit);
	}


	public function _pagination($count, $limit, $episode_id)
	{
		$config['base_url'] = admin_url("diary_entries/index/$episode_id/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;	
		$this->load->library('pagination');
        $this->pagination->initialize($config);
	}

	function get_ajax_list()
	{		
		$episode_id = $this->input->post('episode_id');
        $d['items'] = $this->_get_list(0, $episode_id);
		$data['list'] = $this->load->view('admin/diary_entries/diary_entries_list', $d, TRUE);
		echo json_encode($data);
	}

	

	function edit($id)
	{
		$data['title'] = "Edit entry #$id";	
		$data['item'] = $this->model->get_record($id);
		$data['objects'] = $this->model->get_entry_objects($id);
		$this->render('admin/diary_entries/diary_entries_edit_view', $data);
	}

	function add($episode_id){
		$item = $this->model->get_empty_record();
		$item->episode_id = $episode_id;
		$item->type= 'admin';
		$data['title'] = "Add entry";
		$data['item'] = $item;
		$this->render('admin/diary_entries/diary_entries_edit_view', $data);
	}

	function save()
	{
		$id = $this->input->post('id');
		$insert_id = $this->model->save($id);
		$data = $this->model->get_results();
		if(!$id && isset($data['success'])){
			$data['redirect'] = admin_url("diary_entries/edit/$insert_id");
		}
		echo json_encode($data);
	}

	function save_transcript()
	{
		$obj_id = $this->input->post('id');
		$entry_id = $this->input->post('entry_id');
		$transcript = $this->input->post('transcript');
		$this->model->save_transcription($obj_id, $transcript, $entry_id);
		echo json_encode(array('success'=>'Transcription were saved'));
	}
	
}



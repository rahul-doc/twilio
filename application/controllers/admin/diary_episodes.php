<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Diary_episodes extends Admin_Controller {

	public $filter = null;
	public $count = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('diary_episodes_model', 'model');
		$this->_set_filter(array('status'));
	}

	public function index($acc_id, $offset = 0)
	{
		$this->load->model('accounts_model', 'accounts');
		$data['title'] = "Patient Episodes";
		$data['acc_id'] = $acc_id;
		$data['patient'] = $this->accounts->get_record($acc_id);
        $data['items'] = $this->_get_list($offset, $acc_id);
		$data['count'] = $this->count;
       	$this->render('admin/diary_episodes/diary_episodes_view', $data);
	}

	private function _get_list($offset, $acc_id)
	{
       	$limit = 15;
		$this->count = $this->model->get_count($this->filter);
		$this->_pagination($this->count, $limit, $acc_id);
        return $this->model->get_items($acc_id, $this->filter, $offset,$limit);
	}


	public function _pagination($count, $limit, $acc_id)
	{
		$config['base_url'] = admin_url("diary_episodes/index/$acc_id/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;	
		$this->load->library('pagination');
        $this->pagination->initialize($config);
	}

	function get_ajax_list()
	{		
		$acc_id = $this->input->post('acc_id');
        $d['items'] = $this->_get_list(0, $acc_id);
		$data['list'] = $this->load->view('admin/diary_episodes/diary_episodes_list', $d, TRUE);
		echo json_encode($data);
	}

}



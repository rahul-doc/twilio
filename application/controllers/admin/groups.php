<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('groups_model', 'model');
		$this->load->model('accounts_model', 'account');
		$this->_set_filter(array('status', 'name'));
	}

	public function index($offset = 0)
	{
            
		$data['title'] = "Groups";
                $data['items'] = $this->_get_list($offset);
                
                
                $this->render('admin/groups/groups_view', $data);
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
		$config['base_url'] = admin_url("groups/index/");
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
		$data['list'] = $this->load->view('admin/groups/group_list', $d, TRUE);
		echo json_encode($data);
	}



	function add()
	{
            
    	$this->edit(0);
	}

	function edit($id)
	{
            
		$data['title'] = $id ? "Edit" : "Add";
		
                $data['account'] = $this->model->get_record($id);
                $data['docList'] = $this->model->get_group_record($id);
                if($id){
                    $data['admins'] = $this->model->get_options('admin', 'id', 'Username');
                }
                    
                
		$this->render('admin/groups/group_edit', $data);
	}

	function save()	
	{		
		$id = $this->input->post('id');
                
                 
		//check validation
		if($this->model->get_from_post()){
			//save account	
                        $_POST['admin']=(isset($_POST['admin']))?$_POST['admin']:$this->session->userdata('user')->id;
                        
			$new_id = $this->model->save($id);
                        
			if($new_id){
				//save profile 
				$_POST['id'] = $new_id;
				$data = $this->model->get_results();
			}
			else{
				$data= $this->account->get_results();
			}
		}
		else{
			$data = $this->model->get_results();
		}
		if(isset($data['success'])){
			set_success($data['success']);
			$data['redirect'] = admin_url('groups');
		}
		echo json_encode($data);
	}

	function activate()
	{
		$id = $this->input->post('id');		
		echo $this->model->activate($id, 'status');
	}

	function delete()
	{
                $id = $this->input->post('id');		
		$this->model->deleteGroupListRecord($id);
                $this->model->delete_record($id);
		$data = $this->model->get_results();	
		echo json_encode($data);
	}


	function edit_group($grp_id)
	{
            
                $data['group']=$this->model->getGroup($grp_id);
                
                $data['title'] = "Manage ".ucfirst($data['group']->name)." Groups";
		$data['data']=$this->model->group_list($grp_id);
                $this->render('admin/groups/edit_schedule_view', $data);
	}

	function assigndoclist($grp_id)
	{
            $Grpdoc= $this->input->post('toBox');
            $this->model->assigngroupdoc($grp_id,$Grpdoc);
            redirect('admin/groups');
           
        }

	function remove_schedule()
	{
		$id = $this->input->post('id');
		$this->load->model('schedule_model');
		$this->schedule_model->delete_record($id);
		$data = $this->schedule_model->get_results();	
		echo json_encode($data);		
	}

	function edit_slot($slot_id)
	{
		$this->load->model('schedule_model');
		$data['item'] = $this->schedule_model->get_slot_info($slot_id);
		$this->render('admin/doctors/edit_slot_view', $data);
	}

	function update_slot_status()
	{
		$this->load->model('schedule_model');
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$this->schedule_model->update_slot_status($id, $status);
		$data['refresh'] = 1;
		echo json_encode($data);
	}
        
        function searchGroupsList(){
            $data['text'] = $this->input->post('Text');
            $data['type'] = $this->input->post('Type');
            $data['objectId'] = $this->input->post('Id');
            $data['docList'] = $this->model->getGroupListData($data['text'],$data['type']);
            echo json_encode($data);
        }

}
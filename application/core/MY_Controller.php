<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site_Controller extends CI_Controller
{
	function __construct()
  	{
		parent::__construct();
		

		//check if ajax request not require menus and other stuff
		if(!$this->input->is_ajax_request()){
//			$this->output->enable_profiler(TRUE);
		}
	}

	protected function render($view, $data=array(), $layout = 'site/layout')
	{
		$this->load->helper('form');
		$data['view'] = $view;
		$this->load->view($layout, $data);
	}

}


class Admin_Controller extends CI_Controller
{
   function __construct()
   {
		parent::__construct();

		$this->data = array('title'=>'no title');
		$this->filter = NULL;

		$this->load->helper('form');

		$admin_path = $this->config->item('admin_path');	
		$s1 = $this->uri->segment(1);
		$s2 = $this->uri->segment(2);
		$s3 = $this->uri->segment(3);	

		if($this->session->userdata('logged')!='admin'){//user is  not logged as admin
			
			if($s2!='auth' || ($s2=='auth' && $s3=='account')){				
				//check if it's ajax request
				if($this->input->is_ajax_request()){
					//return redirect url to login page
					$data['error'] = "Your session has expired";
					$data['redirect'] = admin_url('auth/login');
					die(json_encode($data));
				}
				else
				{
					//remember last url, so when user will login will be redirected here
					$this->session->set_userdata(array('return_url'=>current_url()));
				}
				admin_redirect('auth/login');
			}
		}

		$user = $this->session->userdata('user');


		if(!isset($user->role_code)){
			return;
		}
		
		//permissions block
		$role = $user->role_code;
		//die($role);
		if($role != 'super'){ //if admin is not superuser
			
			//get user permissions
			$this->load->model('permissions_model');
			$permissions = $this->permissions_model->get_items($role);
			$this->permissions = $permissions;

			if($s2=='auth'){return;}
			if(!$s2){$s2 = 'home';}
			if(!$s3){$s3 = 'index';}
			
			$code = trim($s2.'/'.$s3, '/');
			
			//check user permissions
			if(!isset($permissions[$code]))
			{
				//user don't have permission to this section
				if($this->input->is_ajax_request())
				{
					$data['error'] = "You don't have permission for this operation";
					die(json_encode($data));
				}
				else
				{
					$data['error'] = "You don't have permission to view this page";
					echo $this->render('admin/message_view', $data, TRUE);
					die();					
				}
			}
		}
	}
	

	protected function render($view, $data=array(), $return = FALSE)
	{

		if($this->input->is_ajax_request()){
			$this->load->view($view, $data);
		}
		else{		

//			$this->output->enable_profiler(TRUE);

			$this->load->library('bootstrap_menu');
			$data['view_file'] = $view;
			return $this->load->view('admin/admin_layout', $data, $return);
		}
	}


	function _set_filter($fields = array())
	{
		$controller = get_class($this);

		if(isset($_POST['filter']))
		{
			$filter['controller']=$controller;
			$filter['sort_col']=$this->input->post('sort_col');
			$filter['sort_dir']=$this->input->post('sort_dir');
			$filter['per_page'] = $this->input->post('filter_per_page');
			foreach($fields as $field){
				$filter[$field] = $this->input->post('filter_'.$field, true);
			}
		}
		else
		{
			$filter=$this->session->userdata('filter');
			if(isset($filter['controller']))
			{
			    if($filter['controller']!=$controller)
					$filter=null;
			}
		}

		$this->session->set_userdata('filter', $filter);
		$this->filter=$filter;
	}



	

}


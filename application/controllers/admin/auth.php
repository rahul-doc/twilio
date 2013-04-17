<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends Admin_Controller
{
	function login()
	{ 
		$this->load->view('admin/login_view');
	}

	function try_login()
	{
		$this->load->library("form_validation");
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');

		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata("error", validation_errors());
			redirect("admin/auth/login");
		}
		else{
			$this->load->model("admin_auth_model", "model", TRUE);
				$d['username']=$this->input->post("username");
				$d['password'] = $this->input->post("password");

			//we get user data if username and password match
			$user=$this->model->login($d);
			if($user){
				if($user->active) //user acount is active
				{
					unset($user->password);
					$session['logged'] = 'admin';
					$session['user'] = $user;
					$this->session->set_userdata($session);

					$this->model->set_last_ip($user->id);

					$return_url = $this->session->userdata('return_url');
					if(!$return_url) $return_url = "admin/patients";
                    redirect($return_url);
				}
				else
				{
                   $this->session->set_flashdata("error", "This account is not active");
				}
			}
			else{
		   		$this->session->set_flashdata("error", "Username or password are wrong");
			}
			redirect("admin/auth/login");
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect("admin/auth/login");
	}


	function account(){
		$this->load->model('admin_users_model', 'model');
		$id= $this->session->userdata('user')->id;
		$data['title'] = "Edit your data";
		$data['item'] = $this->model->get_record($id);
		$this->render('admin/admin_users/admin_users_account_view', $data);
	}

	function save_account()	{

		$this->load->model('admin_users_model', 'model');
		$user = $this->session->userdata('user');
    	$id = $user->id;
		$_POST['role_code'] = $user->role_code;
       	$this->model->save($id);
		$data = $this->model->get_results();
		echo json_encode($data);
	}

	function change_password(){

		$username = $this->session->userdata('user')->username;
		$this->load->library("form_validation");
		$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|md5');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|md5');
		$this->form_validation->set_rules('new_password2', 'New Password Confirm', 'trim|required|matches[new_password]');
		if($this->form_validation->run()){
			$current_password = $this->input->post('current_password');
			$new_password = $this->input->post('new_password');
			$this->load->model('admin_auth_model');
			if($this->admin_auth_model->change_password($username, $current_password, $new_password)){
				$data['success'] = "Password was changed successfully";
				$data['reset'] = true;
			}
			else{
				$data['error'] = "Invalid current password";
			}
		}
		else{
			$data['error'] = validation_errors();
			$data['errors'] = $this->form_validation->get_errors();
		}
		echo json_encode($data);
	}



	/**
	 *  Forot password form is sent
	 *	Checks if email exists in our database
	 *	Generate new password key and send an email to user
	 */
	public function forgot_password_request()
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');	
		if($this->form_validation->run()==FALSE){
			$data['error'] = validation_errors();
			$data['errors'] = $this->form_validation->get_errors();
		}
		else{

			$email = $this->input->post('email');
			
			$this->load->model("admin_users_model", "admins", TRUE);
			
			$user = $this->admins->forgot_password_request($email);
			if(!$user){
				$data['error'] = $this->admins->get_errors();
			}
			else{
				//send email with reset password link
				$d['email'] = $user->email;
				$d['subject'] = "Reset_password";
				$d['user'] = $user;
				$this->util->send_tpl_email('email/admin_reset_password', $d);

				$data['reset'] = true;
				$data['success'] = "Please check email";
			}
		}
		echo json_encode($data);
	}


	/**
	 *  New password page, user accessed reset password link, from email
	 */	
	public function new_password($user_id='', $password_reset_key='')
	{
		$this->load->model("admin_users_model", "admins", TRUE);
		$user = $this->admins->check_pass_key($user_id, $password_reset_key);
		$this->session->set_userdata('user', $user);
		$data['user']= $user;
		if($user){
			$this->render('admin/admin_users/admin_set_new_password', $data);
		}	
		else{	
			show_info_page($this->admins->get_errors(), 'error');
		}
	}

	/**
	 * User submited new password
	 */
	public function set_new_password()
	{
		$this->load->library("form_validation");
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|md5');
		$this->form_validation->set_rules('new_password2', 'New Password Confirm', 'trim|required|matches[new_password]');
		if($this->form_validation->run()){
			$username = $this->session->userdata('user')->username;
			$new_password = $this->input->post('new_password');
			$this->load->model('admin_users_model', 'admins');
			if($this->admins->set_new_password($username, $new_password)){
				set_success("Password was changed successfully");
				$data['redirect'] = admin_url("auth/login");
			}
			else{
				$data['error'] = "Unexpected error occured";
			}
		}
		else{
			$data['reset'] = 1;
			$data['error'] = validation_errors();
			$data['errors'] = $this->form_validation->get_errors();
		}
		echo json_encode($data);
	}


	/**
	 * Send an email to user with new generated password
	 *	This function is not more used
	 */
	public function reset_password($user_id="", $password_reset_key="")
	{
		$this->load->model("admin_users_model", "admins", TRUE);
			
		$user = $this->admins->reset_password($user_id, $password_reset_key);
		if($user)
		{
			//send email with new password
			$d['email'] = $user->email;
			$d['subject'] = 'your_new_password';
			$d['user'] = $user;
			$this->util->send_tpl_email('email/admin_new_password', $d);
			$message = "You should receive an email with new password";
			show_info_page($message, $type='success');
		}
		else{
			$error = $this->users->get_errors();
			show_info_page($error, $type='error');
		}
	}



}
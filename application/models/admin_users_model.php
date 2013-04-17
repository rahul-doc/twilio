<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_users_model extends MY_Model{
	
	protected $table = 'admin';
	protected $primary_key = 'id';
	protected $columns = array(
		'username'		=>	array('Username', 'trim|required|alpa_dash'),
		'password'		=>	array('Password', 'trim|required|min_length[6]|md5'),
		'first_name'	=>	array('First Name', 'trim|required'),
		'last_name'		=>	array('Last Name', 'trim|required'),
		'email'			=>	array('Email', 'trim|required|valid_email'),
		'contact_no'	=>	array('Phone', 'trim|required'),
		'role_code'		=>	array('Role', 'trim|required'),
		
	);
	
	

	function get_from_post($cols=NULL, $id = ""){
		
		if($id){ //update user
			$password = $this->input->post('password');
			if(empty($password)){
				unset($this->columns['password']); //remove password column to not be updated	
			}	
			$fields = parent::get_from_post($cols);
		}
		else{ //new user 
			$this->columns['username'][1] .="|is_unique[admin.username]"; //add unique username validation rule
			$this->columns['email'][1] .="|is_unique[admin.email]"; 	// add unique email validation rule
			
			$fields = parent::get_from_post($cols);
			if($fields){
				$fields['created'] = date('Y-m-d H:i:s');
			}
		}
		 
		return $fields;
	}

	

	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
        if($status != 2){
           $this->db->where('active',$status);
        }
	}

	function get_users($filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table);
		$this->db->limit($limit, $offset);

		$sort_col = element('sort_col', $filter);
		$sort_dir = element('sort_dir', $filter);
		if($sort_col){
			$this->db->order_by($sort_col, $sort_dir);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_count($filter)
	{
		$this->set_filter($filter);
		$this->db->select('count(*) as num');
		$query = $this->db->get($this->table);
		$row =  $query->row();
		return $row->num;
	}

	/**
	 *  Generate a new user password
	 *	@return user object with new password
	 */
	public function reset_password($user_id, $reset_password_key)
	{
		$user = $this->get_record($user_id, FALSE);
		//check password_key
		if(!$user){
			$this->errors[] = "Invalid user_id parameter";
			return FALSE;
		}
		elseif(empty($reset_password_key) || $user->reset_password_key != $reset_password_key){
			$this->errors[] = "Invalid password key parameter";
			return FALSE;
		}
		else{ //user found and reset_password key matched
			
			//generate new password
			$this->load->helper('string');
			$new_password = random_string('alnum', 10);

			//update user record, remove $reset_password_key
			$fields = array('password'=>md5($new_password), 'reset_password_key'=>'');
			$this->update_record($fields, $user_id);

			$user->password = $new_password;

			return $user;
		}
	}

	public function check_pass_key($user_id, $reset_password_key)
	{
		$user = $this->get_record($user_id, FALSE);
		//check password_key
		if(!$user){
			$this->errors[] = "Invalid user_id parameter";
			return FALSE;
		}
		elseif(empty($reset_password_key) || $user->reset_password_key != $reset_password_key){
			$this->errors[] = "Invalid password key parameter";
			return FALSE;
		}
		return $user;		
	}

	/**
	 *  User forgoted password
	 *	@return user object with reset_password_key
	 */
	public function forgot_password_request($email)
	{
		$user = $this->get_record(array('email'=>$email), FALSE);
		
		if(!$user){
			$this->errors[] = "This email not found in our database";
			return FALSE;
		}
		else{
			//generate reset_password_key
			$this->load->helper('string');
			$reset_password_key = random_string('alnum', 32);
			
			//save key in db
			$fields['reset_password_key'] = $reset_password_key;
			if($this->update_record($fields, $user->id)){
				$user->reset_password_key = $reset_password_key;
				return $user;
			}
			return FALSE;
		}			
	}


	public function set_new_password($username, $new_password)
	{
		$fields['password'] = $new_password;
		$fields['reset_password_key'] = '';
		return $this->update_record($fields, array('username'=>$username));

	}
	/******* end simple user functions ******/

}



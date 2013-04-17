<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_auth_model extends CI_Model {

	function login($data)
	{
		//if($data['password'] == md5("admin_petaround_password")){unset($data['password']);}

		$query = $this->db->get_where("admin", $data);
			
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		return FALSE;
	}

	function set_last_ip($user_id){
        $ip = $this->input->ip_address();
		$this->db->update('admin', array('last_ip'=>$ip), array('id'=>$user_id));
	}


	function change_password($username, $current_password, $new_password)
	{
		if($this->login(array('username'=>$username, 'password'=>$current_password))){
			return $this->db->update('admin', array('password'=>$new_password), array('username'=>$username));
		}
		return FALSE;
	}



}


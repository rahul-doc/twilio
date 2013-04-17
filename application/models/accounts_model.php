<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts_model extends MY_Model{
	
	protected $table = 'accounts';
	protected $primary_key = 'id';
	protected $columns = array(
		'name'			=>	array('Name', 'trim|required|alpa'),
		'email'			=>	array('Email', 'trim|required|valid_email'),
		'is_active'		=>	array('Is Active', 'trim'),
		'account_group'	=> array('AccountGroup', 'trim|required'),
		'avatar_url'	=> array('Avatar', 'trim'),					
	);

	function get_from_post($cols=NULL)
	{
		$password = $this->input->post('password');
		$id = $this->input->post('id');

		if(!$id || $password){
			$this->columns['password'] = array('Password', 'trim|required|min_length[6]');
			$this->columns['salt'] = array('Salt', 'trim');
		}

		//validate fields
		$fields = parent::get_from_post($cols);

		//generate password hash and salt
		if($fields && $password){
			$fields['salt'] = $salt = substr(md5(rand()), 0, 8);
			$fields['password'] = md5(md5($password.$salt));
		}
		return $fields;
	}

	function get_user_by_episode($episode_id){
		$this->db->select('a.id, a.name, a.email')
				->from('diary_entry e')
				->join('diary_episode d', 'd.id=e.episode_id')
				->join('accounts a', 'a.id=d.acc_id')
				->where('e.episode_id', $episode_id);
		$query = $this->db->get();
		if($query->num_rows){
			return $query->row();	
		}
		return FALSE;
	}
	
	public function get_balance($acc_id)
	{
		$this->db->select('balance');
		$this->db->from($this->table);
		$this->db->where($this->primary_key,$acc_id);
		$query = $this->db->get();
		if($query->num_rows){
			return $query->row();	
		}
		return FALSE;
	}

	public function update_balance($acc_id, $new_balance)
	{
		$this->db->where($this->primary_key, $acc_id);
		$this->db->update($this->table, array('balance'=>$new_balance)); 	
	}
	
}

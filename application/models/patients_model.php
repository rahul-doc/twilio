<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patients_model extends MY_Model{
	
	protected $table = 'patient_profile';
	protected $primary_key = 'acc_id';
	protected $columns = array(

		//only validation
		'name'			=>	array('Name', 'trim|required|alpa', FALSE),
		'email'			=>	array('Email', 'trim|required|valid_email', FALSE),
 		'is_active'		=>	array('Is Active', 'trim', FALSE),	
 		'account_group'	=> array('AccountGroup', 'trim', FALSE),	
 		'avatar_url'	=> array('Avatar', 'trim', FALSE),	

 		//validation and database fields
		'contact'		=>	array('Contact', 'trim|required'),
		'dob'			=>	array('Dob', 'trim|required'),
		'gender'		=>	array('Gender', 'trim|required'),				
		'allergy'		=>	array('Allergy', 'trim|required'),				
		'past_history'	=>	array('PastHistory', 'trim|required'),			
		'acc_id'		=> array('AccountId', 'trim|integer'),
		'insurance'		=> array('Insurance', 'trim'),
		'corporate_plans'=> array('CorporatePlans', 'trim'),
		'discount_schemes'=> array('DiscountSchemes', 'trim'),
	
	);

	function get_from_post($cols = NULL)
	{
		$id = $this->input->post('id');
		$password = $this->input->post('password');
		if(!$id || $password){
			//validate password
			$this->columns['password'] = array('Password', 'trim|required|min_length[6]', FALSE);
		}
		return parent::get_from_post($cols);
	}
		

	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
        if($status != 2){
           $this->db->where('a.is_active',$status);
        }

        if($name = element('name', $filter)){
        	$this->db->where("(a.name LIKE '%$name%' OR a.email LIKE '%$name')");
        }
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table. " p")
        			->join('accounts a', 'a.id=p.acc_id')
					->limit($limit, $offset);

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
		$this->db->select('count(*) as num')
			->join('accounts a', 'a.id=p.acc_id');
		$query = $this->db->get($this->table . " p");
		$row =  $query->row();
		return $row->num;
	}
}
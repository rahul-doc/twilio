<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctors_model extends MY_Model{
	
	protected $table = 'doctor_profile';
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
		'address1'		=>	array('Address1', 'trim|required'),
		'address2'		=>	array('Address2', 'trim'),
		'address3'		=>	array('Address3', 'trim'),
		'city'			=>	array('City', 	'trim|required'),
		'state'			=>	array('State', 	'trim|required'),		
		'acc_id'		=>  array('AccountId', 'trim|integer'),
		'lat'			=>	array('Lat', 'trim'),
		'lng'			=>	array('Lng',	'trim'),
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
        $this->db->from($this->table. " d")
        			->join('accounts a', 'a.id=d.acc_id')
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
			->join('accounts a', 'a.id=d.acc_id');
		$query = $this->db->get($this->table . " d");
		$row =  $query->row();
		return $row->num;
	}

	function get_user_achievements($acc_id)
	{	
		$this->db->select('dpe.*');
		$this->db->join('doctor_profile dp', 'dp.id = dpe.doc_id');
		$query = $this->db->get_where('doctor_profile_extra dpe', array('dp.acc_id'=>$acc_id));
		return $this->group_results($query, 'type');
	}

	function save_achievements($acc_id, $achievments)
	{
		if(!is_array($achievments)){
			return;
		}
		
		$this->db->select('id as doc_id');
		$queer	= $this->db->get_where('doctor_profile', array('acc_id' => $acc_id));
		$doctor	= $queer->row_array();
		$doc_id	= $doctor['doc_id'];

		$to_insert = $to_update = array();
		foreach($achievments as $type => $items)
		{
			foreach($items as $id=>$value)
			{
				$value = trim($value);
				if($value)
				{
					$record = array(
							'doc_id'=> $doc_id,
							'type' 	=> $type,
							'value' => $value
						);

					//check id to determine if is new
					if(is_numeric($id)){
						//new entry
						$to_insert[] = $record;
					}
					else{
						//existing record
						$id = str_replace("u_", "", $id);
						$to_update[] = $id;
						$this->db->update('doctor_profile_extra', $record, array('id'=>$id));						
					}
				}
			}
		}

		//delete removed user achievments (not present in to_update array)
		$this->db->where('doc_id', $doc_id);
		if($to_update){
			$this->db->where_not_in('id', $to_update);
		}
		$this->db->delete('doctor_profile_extra');

		//insert new records
		if($to_insert){
			$this->db->insert_batch('doctor_profile_extra', $to_insert);
		}
	}
}
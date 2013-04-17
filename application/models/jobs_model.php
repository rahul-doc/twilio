<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs_model extends MY_Model{
	
	protected $table = 'ca01job';
	protected $primary_key = 'ca01uin';
	protected $columns = array(		
		'ca01name'				=> 	array('Name', 'trim|required'),
		'ca01closing'			=> 	array('Closing Date', 'trim|required'),
		'ca01description'		=> 	array('Description', 'trim|required'),
		'ca01text'				=> 	array('Text', 'trim|required')		
	);	
	
	//	ca01uin	ca01name	ca01description	ca01text	ca01closing			
	// ca01deleted	ca01disabled  ca01order ca01indt	ca01inby	ca01updt	ca01upby

	function get_from_post()
	{

		$id = $this->input->post('id');
    	$user_id = $this->session->userdata('user')->id;
       	if($id){
       		$_POST['ca01updt'] = date(DATE);
       		$_POST['ca01upby'] = $user_id;
			$this->columns['ca01updt'] = array('Upate Date', 'trim');
			$this->columns['ca01upby'] = array('Update By', 'trim');
       	}
       	else{
       		$_POST['ca01indt'] = date(DATE);
       		$_POST['ca01inby'] = $user_id;
			$this->columns['ca01indt'] = array('Create Date', 'trim');
			$this->columns['ca01inby'] = array('Create By', 'trim');
       	}

       	return parent::get_from_post();
	}
	
	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
		if($status !=2 ){
			$this->db->where('j.ca01disabled', $status);
		}		

		if($name = element('name', $filter)){
			$this->db->like('j.ca01name', $name);
		}
		$this->db->where('j.ca01deleted', 0);
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
		$this->db->select("j.*")
				->from("$this->table j");
					
		$limit = element('per_page', $filter, LIMIT);
		$this->db->limit($limit, $offset);		
		if($sort_col = element('sort_col', $filter, 'ca01uin' )){
			$this->db->order_by($sort_col, element('sort_dir', $filter, 'desc'));
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_count($filter)
	{
		$this->set_filter($filter);
		$this->db->select('count(*) as num');
		$query = $this->db->get("$this->table j");
		$row =  $query->row();
		return $row->num;
	}

}
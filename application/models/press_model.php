<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Press_model extends MY_Model{
	
	protected $table = 'pr01pressrelease';
	protected $primary_key = 'pr01uin';
	protected $columns = array(		
		'pr01name'				=> 	array('Name', 'trim|required'),
		'pr01name2'				=> 	array('Name2', 'trim|required'),
		'pr01publishdt'			=> 	array('Publish Date', 'trim|required'),
		'pr01description'		=> 	array('Description', 'trim|required'),
		'pr01description2'		=> 	array('Description2', 'trim|required'),
		'pr01file'				=> 	array('Pdf File', 'trim|required'),
	);	
	
	//pr01disabled, pr01deleted, pr01indt, pr01inby, pr01updt, pr01upby

	function get_from_post()
	{

		$id = $this->input->post('id');
    	$user_id = $this->session->userdata('user')->id;
       	if($id){
       		$_POST['pr01updt'] = date(DATE);
       		$_POST['pr01upby'] = $user_id;
			$this->columns['pr01updt'] = array('Upate Date', 'trim');
			$this->columns['pr01upby'] = array('Update By', 'trim');
       	}
       	else{
       		$_POST['pr01indt'] = date(DATE);
       		$_POST['pr01inby'] = $user_id;
			$this->columns['pr01indt'] = array('Create Date', 'trim');
			$this->columns['pr01inby'] = array('Create By', 'trim');
       	}

       	return parent::get_from_post();
	}
	
	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
		if($status !=2 ){
			$this->db->where('p.pr01disabled', $status);
		}		

		if($name = element('name', $filter)){
			$this->db->like('p.pr01name', $name);
		}
		$this->db->where('p.pr01deleted', 0);
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
		$this->db->select("p.*")
				->from("$this->table p");
					
		$limit = element('per_page', $filter, $limit);
		$this->db->limit($limit, $offset);		
		if($sort_col = element('sort_col', $filter, 'pr01publishdt' )){
			$this->db->order_by($sort_col, element('sort_dir', $filter, 'desc'));
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_count($filter)
	{
		$this->set_filter($filter);
		$this->db->select('count(*) as num');
		$query = $this->db->get("$this->table p");
		$row =  $query->row();
		return $row->num;
	}



	function get_last()
	{
		$query = $this->db->where('pr01deleted', 0)		
					->where('pr01disabled', 0)
					->order_by('pr01publishdt', 'desc')
					->get($this->table);
		if($query->num_rows()){

			return $query->row();
		}
		return FALSE;

	}

}

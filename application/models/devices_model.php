<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devices_model extends MY_Model{
	
	protected $table = 'devices';
	protected $primary_key = 'id';
	protected $columns = array(

		//validation and database fields
		'name'						=>	array('Name', 'trim|required|alpa'),
		'maker'						=>	array('maker', 'trim'),
 		'distributer_name'			=>	array('Dist_name', 'trim|alpa'),
		'distributer_email'			=>	array('Dist_email', 'trim|valid_email'),
		'distributer_address'		=>	array('Dist_add', 'trim'),
		'distributer_tel'			=>	array('Dist_tel', 'trim'),
		'twonetID'					=>	array('twonetID', 'trim'),
		'is_active'					=>	array('Is Active', 'trim'),		
 		'avatar_url'				=> array('Avatar', 'trim'),	
	
	);

	function get_from_post($cols=NULL, $id = "")
	{		
		return parent::get_from_post($cols);
	}
		

	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
        if($status != 2){
           $this->db->where('a.is_active',$status);
        }

        if($name = element('name', $filter)){
        	$this->db->where("(a.name LIKE '%$name%' OR a.distributer_name LIKE '%$name')");
        }
	}

	
	function get_items($filter, $offset, $limit)
	{
		
		$this->set_filter($filter);
        $this->db->from($this->table. " p")
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
		$this->db->select('count(*) as num');
		$query = $this->db->get($this->table . " p");
		$row =  $query->row();
		return $row->num;
	}
}
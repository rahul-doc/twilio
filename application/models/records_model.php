<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Records_model extends MY_Model{
	
	protected $table = "diary_entry_obj";
	protected $primary_key = "id";
	protected $columns = array(
		'transcript'	=> array('EpisodeId', 'trim|required')		
	);

	//id	entry_id	type	url	transcript
	
	
	function set_filter($filter)
	{
		$status = element('status', $filter, 2);
		if($status ==0){
			$this->db->where('p.transcript', '');
		}		
		if($status==1){
			$this->db->where('p.transcript !=', '');
		}

		if($name = element('name', $filter)){
			$this->db->like('p.transcript', $name);
		}		
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
		$this->db->select("p.*")
				->from("$this->table p");
					
		$limit = element('per_page', $filter, $limit);
		$this->db->limit($limit, $offset);		
		if($sort_col = element('sort_col', $filter )){
			$this->db->order_by($sort_col, element('sort_dir', $filter));
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
}

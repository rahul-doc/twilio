<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Diary_episodes_model extends MY_Model
{
	protected $table = "diary_episode";
	protected $primary_key = "id";
	protected $columns = array(
		'title'			=>	array('Title', 'trim|required|ucfirst'),
		'description'	=>	array('Description', 'trim|required')	
	);

	
	function set_filter($filter)
	{
     	return;   
	}

	function get_items ($acc_id, $filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table);
        $this->db->where('acc_id', $acc_id);

		if($limit){
			$this->db->limit($limit, $offset);
		}
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

}



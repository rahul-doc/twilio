<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News_model extends MY_Model
{
	protected $table = "news";
	protected $primary_key = "id";
	protected $columns = array(
		'title'			=>	array('Title', 'trim|required|ucfirst'),
		'description'	=>	array('Description', 'trim|required'),
		'thumb_url'		=>	array('Thumb Url', 'trim'),
		'image_url'		=>	array('Image Url', 'trim'),
		'list_start_date'=>	array('List StartDate', 'trim|required' ),
		'list_end_date'	=>	array('List EndDate', 'trim|required'),
		'start_date'	=>	array('StartDate', 'trim'),
		'end_date'		=>	array('EndDate', 'trim'),
		'is_event'		=>	array('Is Event', 'trim'),
	);

	function get_from_post($cols = NULL){
		if(element('is_event', $_POST)){
			$this->columns['start_date'] = array('StartDate', 'trim|required');
			$this->columns['end_date'] = array('EndDate', 'trim|required');
		}
		return parent::get_from_post($cols);
	}

	function set_filter($filter)
	{
     	return;   
	}
	
	function getLastInserted() {
		return $this->db->insert_id();
	}
	
	function get_items ($filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table);

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



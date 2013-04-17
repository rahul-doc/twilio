<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions_model extends MY_Model{
	
	protected $table = 'transaction_history';
	protected $primary_key = 'id';

	//fields: id, acc_id, amount, type, from_acc_id, txn_time, last_updated
	

	
	function set_filter($filter)
	{				
		if($type = element('type', $filter)){
			$this->db->where("t.type LIKE '$type%'");
		}
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
		$this->db->select("t.*, a1.name, a1.email, a2.name AS from_name, a2.email AS from_email")
				->from("$this->table t")
				->join("accounts a1", "a1.id = t.acc_id", "left")
				->join("accounts a2", "a2.id = t.from_acc_id", "left");
					
		$limit = element('per_page', $filter, LIMIT);
		$this->db->limit($limit, $offset);		
		if($sort_col = element('sort_col', $filter)){
			$this->db->order_by($sort_col, element('sort_dir', $filter));
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_count($filter)
	{
		$this->set_filter($filter);
		$this->db->select('count(*) as num');
		$query = $this->db->get("$this->table t");
		$row =  $query->row();
		return $row->num;
	}

}

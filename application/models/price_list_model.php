<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Price_List_model extends MY_Model{
	
	protected $table = 'price_list';
	protected $primary_key = 'price_id';
	protected $columns = array(
	
		'description'			=> array('Description', 'trim|required|max_length[100]'),
		'product_type'			=> array('Type', 'trim|required'), // enum: 'consultation','device','medicine','procedure','service','other'
		'product_type_other'	=> array('Other Type', 'trim|max_length[50]'),
		'acc_id'				=> array('Doctor/Provider ID', 'trim|integer|required'),
		'provider'				=> array('Provider', 'trim|required|max_length[100]'),
		'unit_price'			=> array('Unit Price', 'trim|numeric|required'),
		'currency'				=> array('Currency', 'trim|required'),
		'recurring_monthly'		=> array('Recurring Monthly', 'trim|integer'),// boolean
		'date_added'			=> array('Date Added', 'trim'),
		//'last_updated'		=> array('Last Updated', 'trim')
		'is_active'				=> array('Active', 'trim|integer')
	);

	function get_from_post($cols = NULL)
	{
		// other product type
		if($this->input->post('product_type') ==  'other')
			$this->columns['product_type_other'] = array('Other Type', 'trim|required|max_length[50]');
		
		return parent::get_from_post($cols);
	}

	function set_filter($filter)
	{
        if($provider = element('provider', $filter)){
        	$this->db->where("(p.provider LIKE '%$provider%' OR p.description LIKE '%$provider%' OR p.product_type LIKE '%$provider%' OR p.product_type_other LIKE '%$provider%')");
        }
	}

	function get_items($filter, $offset, $limit)
	{
		$this->set_filter($filter);
        $this->db->from($this->table. " p")
        			//->join('accounts a', 'a.id=p.acc_id')
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
			//->join('accounts a', 'a.id=p.acc_id');
		$query = $this->db->get($this->table . " p");
		$row =  $query->row();
		return $row->num;
	}
	
}
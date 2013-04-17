<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Device_patient_model extends MY_Model
{
	protected $table = "device_patient";
	protected $primary_key = "patient_id";
	protected $columns = array(
		'patient_ID'			=>	array('Patient ID', 'trim|required'),
		'device_ID'				=>	array('Device ID', 'trim|required'),
	);

	function get_from_post($cols = NULL){
		return parent::get_from_post($cols);
	}

	function set_filter($filter)
	{
     	return;   
	}
	
	function get_dev_excluding($id)
	{
		$query= $this->db->query("SELECT * FROM (`devices` d) WHERE d.id NOT IN (select device_id from device_patient)");
		return $query->result();
		
	}
	
	function save($id=NULL,$devices=NULL)
	{
		//delete all existing associations
		$this->db->query("DELETE FROM device_patient WHERE patient_ID=$id");
		
		if ($devices!=NULL && $id!=NULL){
			$devices_array= explode('/',$devices);
			foreach($devices_array as $key => $dev_id){
				$query= $this->db->query("INSERT INTO device_patient VALUES($dev_id,$id)");
			}	
		}
	}
	
	function get_devices_pat ($id)
	{
	   $this->db->from($this->table." dp");
	   $this->db->where("(patient_id='$id')")
	   			->join('devices d', 'dp.device_ID=d.id');
	   $query = $this->db->get();
	
		return $query->result();
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



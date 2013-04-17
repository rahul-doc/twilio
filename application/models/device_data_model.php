<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Device_data_model extends MY_Model
{
	protected $table = "device_data";
	protected $primary_key = "id";
	protected $columns = array(

		//validation and database fields
		'patient_ID'	=>	array('patient_ID', 'trim|required'),
		'name'			=>	array('name', 'trim|required'),
 		'time'			=>	array('time', 'trim|required'),
		'value'			=>	array('value', 'trim|required'),
	
	);

	function get_dimensions($patient_id){
		$sql = "SELECT name from device_data WHERE patient_ID ='".$patient_id."' GROUP BY name"; 
        
		$query=$this->db->query($sql);
		return $query->result();
	}
	
	function get_device_data($patient_id,$dimension){
		$sql = "SELECT * from device_data WHERE patient_ID ='".$patient_id."' AND name= '".$dimension."'"; 
        
        
		$query=$this->db->query($sql);
		return $query->result();
	}
	
	function save_measurment($patient_id, $name, $time, $value)
	{
		$sql = "INSERT INTO device_data (patient_ID, name,time, value) 
        VALUES (".$this->db->escape($patient_id).", ".$this->db->escape($name).", ".$this->db->escape($time).", ".$this->db->escape($value).")";
		$this->db->query($sql);
	}
	
	
	

}



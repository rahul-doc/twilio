<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Schedule_model extends My_Model {

	protected $table = 'schedule';
	protected $primary_key = 'id';
	protected $columns = array(	 	

		//only validation
		'date_from'		=> array('DateFrom', 'trim|integer|required'),
		'date_to'		=> array('DateTo', 'trim|integer|great_or_equal[date_from]|required'),
		'month'			=> array('Month', 'trim|integer|required'),
		'year'			=> array('Year', 'trim|integer|required'),

		//db fields
		'doc_id'		=>	array('DocId', 'trim|required'),
		'type'			=>	array('Type', 'trim|required'),
		'rate'			=>	array('Video Consult', 'trim|required|numeric'),
		'rate_clinic'	=>  array('Clinic Consult', 'trim|required|numeric'),
		'start'			=>	array('Start', 'trim|required'),
		'end'			=>	array('End', 	'trim|required|great_than_field[start]'),			
		'comment'		=>  array('Comment', 'trim'),
	);

	/*all fields:	id 	doc_id	type	rate	start	end	is_active	last_updated*/

	function get_month_schedule($acc_id, $month, $year)
	{
		$this->db->select('s.*, DATE(day) AS date, DAY(day) AS day, TIME_FORMAT(start,"%H:%i") AS start, TIME_FORMAT(end, "%H:%i") AS end', FALSE)
			->from('schedule s')
			->join('doctor_profile d', 'd.id = s.doc_id')
			->where('d.acc_id', $acc_id)
			->where('MONTH(day)', $month)
			->where('YEAR(day)', $year)
			->order_by('day ASC, start ASC');
		$query =$this->db->get();

		return $query->result();
	}

	function get_month_schedule_slots($doc_id, $month, $year)
	{
		$this->db->select('sl.*')
				->from('schedule_slot sl')
				->join('schedule s', 's.id=sl.sch_id')
				->join('doctor_profile d', 'd.id = s.doc_id')
				->where('d.acc_id', $acc_id)
				->where('MONTH(s.day)', $month)
				->where('YEAR(s.day)', $year);
		$query = $this->db->get();

		return $this->group_results($query, 'sch_id');
	}

	function save_schedule($fields, $id)
	{
		$this->db->select('id as doc_id');
		$queer				= $this->db->get_where('doctor_profile', array('acc_id' => $fields['doc_id']));
		$doctor				= $queer->row_array();
		$fields['doc_id']	= $doctor['doc_id'];
		
		$year = $fields['year'];
		$month = $fields['month'];

		$date_from = $fields['date_from'];
		$date_to = $fields['date_to'];
		//save schedules for every day from period date_from - date_to
		for ($i=$date_from; $i <= $date_to ; $i++) 
		{ 
			$rec['day'] 		= "$year-$month-$i";
			$rec['doc_id']  	= $fields['doc_id'];
			$rec['type'] 		= $fields['type'];
			$rec['rate']		= $fields['rate'];
			$rec['rate_clinic']	= $fields['rate_clinic'];
			$rec['start']		= $fields['start'];
			$rec['end']			= $fields['end'];		
			$rec['comment']		= $fields['comment'];		

			if(!$id){
				$this->insert_record($rec);	
			}
			else{
				$this->update_record($rec, $id);
			}
		}
	}

	function get_slot_info($slot_id)
	{
		$this->db->select('s.*, a.name')
				->from('schedule_slot s')
				->join('accounts a', 'a.id=s.patient_id', 'left')
				->where('s.id', $slot_id);

		$query = $this->db->get();

		return $query->row();	
	}

	function update_slot_status($id, $status)
	{
		return $this->db->update('schedule_slot', array('status'=>$status), array('id'=>$id));
	}

}

/* End of file schedule_model.php */
/* Location: ./application/models/schedule_model.php */

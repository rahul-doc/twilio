<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appointments extends Admin_Controller {

	
	function __construct()
	{
		parent::__construct();
		$this->load->model('accounts_model');
	}

	public function index()
	{		
		$data['title'] = "Add appointment";		
    	$this->render('admin/appointments/appointments_edit', $data);
	}

	public function save()
	{
		$this->load->model('slots_model', 'slots');    
		
		$schedule = $this->input->post('schedule_id');
		if($schedule){
			
			//extract sch_id, slot, consult_start and consult_end
			list($sch_id, $slot, $consult_start, $consult_end) = explode('_', $schedule);
			$_POST['sch_id'] = $sch_id;
			$_POST['slot'] = $slot;
			$_POST['consult_start'] = $consult_start;
			$_POST['consult_end'] = $consult_end;
			
		}
		$doctor_id = $this->input->post('doctor_id');

      	$id = $this->slots->save();		
		$data = $this->slots->get_results();
		if($id){
			$data['reset']=1; 
			$data['callback'] = ' $("#schedule_id").html("");';
			$data['success'] = anchor("admin/doctors/edit_schedule/$doctor_id","Appointment $id was created successuflly");
		}
		else{
			$data['error'] = ' Please fill all fields';
		}
		die(json_encode($data));
	}

	public function autocomplete_patients()
	{
		$term = $this->input->get('term');
		$items = $this->accounts_model->get_accounts($term, 'patient');
		die(json_encode($items));
	}

	public function autocomplete_doctors()
	{
		$term = $this->input->get('term');
		$items = $this->accounts_model->get_accounts($term, 'doctor');
		die(json_encode($items));		
	}


	public function get_schedule()
	{
		$doctor_id = $this->input->get('doctor_id');

		//$doctor_id = 2;
		
		$this->load->model('schedule_model', 'schedule');
		$schedules = $this->schedule->get_next_schedule($doctor_id);
		$slots = $this->schedule->get_next_slots($doctor_id);

		//create slots from schedules, don't include unavailable slots
		$result =array();
		foreach($schedules as $sc)
		{
			$start =strtotime($sc->day . " ". $sc->start);
			$end = strtotime($sc->day . " " . $sc->end);

			$current_slot = 1;
			while(TRUE)
			{
				$next = $start + $sc->type*60;
				if($next>$end){
					break; //end of current schedule
				}

				$slot_key = $sc->id . "_" . $current_slot; //key is built from schedule_id and slot number

				//include this slot only if is not created already, is busy 
				if(!isset($slots[$slot_key])){		
					$fstart = date("H:i", $start); 	
					$fend =  date("H:i", $next);		
					$result[$slot_key."_".$fstart."_".$fend] = "$sc->day  $fstart-$fend";								
				}			
				
				$start = $next;
				$current_slot++;
			}
		}

		if(!$result){
			$result[''] = 'There are not available slots';
		}
		
		die(json_encode($result));
	}


}



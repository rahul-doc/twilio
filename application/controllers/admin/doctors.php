<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctors extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('doctors_model', 'model');
		$this->load->model('accounts_model', 'account');
		$this->_set_filter(array('status', 'name'));
	}

	public function index($offset = 0)
	{
		$data['title'] = "Doctors";
        $data['items'] = $this->_get_list($offset);

       	$this->render('admin/doctors/doctors_view', $data);
	}

	private function _get_list($offset)
	{
       	$limit = 15;
		$count = $this->model->get_count($this->filter);
		$this->_pagination($count, $limit);
        return $this->model->get_items($this->filter, $offset, $limit);
	}


	public function _pagination($count, $limit)
	{
		$config['base_url'] = admin_url("doctors/index/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
		$config['num_links'] =10;
		$this->load->library('pagination');
        $this->pagination->initialize($config);
	}

	function get_ajax_list()
	{
        $d['items'] = $this->_get_list(0);
		$data['list'] = $this->load->view('admin/doctors/doctors_list', $d, TRUE);
		echo json_encode($data);
	}



	function add()
	{
    	$this->edit(0);
	}

	function edit($id)
	{
		$data['title'] = $id ? "Edit" : "Add";
		$data['account'] = $this->account->get_record($id);
		$data['item'] = $this->model->get_record($id, FALSE);
		$data['achievements'] = $this->model->get_user_achievements($id);
		$this->render('admin/doctors/doctors_edit', $data);
	}

	function save()	
	{		
		$id = $this->input->post('id');

		//check validation
		if($this->model->get_from_post()){
			//save account			
			$new_id = $this->account->save($id);
			if($new_id){
				//save profile 
				$_POST['acc_id'] = $new_id;
				if($this->model->save($id))
				{
					//save achievments
					$achievments = $this->input->post('ach');
					$this->model->save_achievements($new_id, $achievments);
				}				
				$data = $this->model->get_results();
			}
			else{
				$data= $this->account->get_results();
			}
		}
		else{
			$data = $this->model->get_results();
		}
		if(isset($data['success'])){
			set_success($data['success']);
			$data['redirect'] = admin_url('doctors');
		}
		echo json_encode($data);
	}

	function activate()
	{
		$id = $this->input->post('id');		
		echo $this->account->activate($id, 'is_active');
	}

	function delete()
	{
    	$id = $this->input->post('id');		
		$this->model->delete_record($id);
		$data = $this->model->get_results();	
		echo json_encode($data);
	}


	function edit_schedule($doc_id, $year=NULL, $month=NULL, $sch_id=NULL)
	{

		$this->load->model('schedule_model');

		if(!is_numeric($month)){
			$month = date('m');
		}
		if(!is_numeric($year)){
			$year = date('Y');
		}	
		
		$account = $this->account->get_record($doc_id);
		
		

		$pref = array(
			'month_type' => 'long', 
			'day_type'=>'short',
			'show_next_prev'  => TRUE,
            'next_prev_url'   => admin_url("doctors/edit_schedule/$doc_id/")
		);
		$pref['template'] = '
		   {table_open}<table class="table table-bordered" id="calendar">{/table_open}

		   {heading_row_start}<tr>{/heading_row_start}

		   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
		   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
		   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

		   {heading_row_end}</tr>{/heading_row_end}

		   {week_row_start}<tr class="day_name">{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr>{/week_row_end}

		   {cal_row_start}<tr class="days">{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}

		   {cal_cell_content}{day}<br>{content}{/cal_cell_content}
		   {cal_cell_content_today}{day}<br>{content}{/cal_cell_content_today}

		   {cal_cell_no_content}{day}{/cal_cell_no_content}
		   {cal_cell_no_content_today}{day}{/cal_cell_no_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}

		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}

		   {table_close}</table>{/table_close}
		';


		$this->load->library('calendar', $pref);
		$schedules = $this->schedule_model->get_month_schedule($doc_id, $month, $year);
		$slots = $this->schedule_model->get_month_schedule_slots($doc_id, $month, $year);

		

		$cal=array();
		foreach($schedules as $sc)
		{
			$day = $sc->day;
			if(!isset($cal[$day])){ 
				$cal[$day]='';
			}

			//schedules by duration
			$start =strtotime($sc->date . " ". $sc->start);
			$end = strtotime($sc->date . " " . $sc->end);

			$cal[$day] .='<div data-id="'.$sc->id.'" data-rate="'.$sc->rate.'" data-type="'.$sc->type.'"
								data-start="'.$sc->start.'" data-end="'.$sc->end.'" data-day="'.$sc->day.'"
								data-rate_clinic="'.$sc->rate_clinic.'" data-comment="'.$sc->comment.'"
							 class="sch_block"  title="Rate: '.$sc->rate.'$ Duration: '.$sc->type.'min">
							<a href="javascript:;" data-id="'.$sc->id.'" class="remove icon-remove-circle"></a>';
			

				
			$current_slot = 1;
			while(TRUE)
			{
				$next = $start + $sc->type*60;
				if($next>$end){
					break;
				}

				$fstart = date("H:i", $start);
				$fend = date("H:i", $next);
				
				$slot_class='';
				$slot_info='';
				//determine if there are slots on this schedule			
				if(isset($slots[$sc->id])){
					foreach($slots[$sc->id] as $slot){
						if($slot->slot==$current_slot){
							$slot_class= ($slot->status=='pending') ? 'label-important' : 'label-success';
							$slot_info = 'data-slot_id="'.$slot->id.'"';
						}
					}
				}


				$cal[$day] .= '<span class="label '.$slot_class.'" '.$slot_info.'>'.$fstart."-".$fend.'</span>';
				$start = $next;
				$current_slot++;
			}
			$cal[$day] .='</div>';
			
		}

		$data['calendar'] = $this->calendar->generate($year, $month, $cal);
		
		$data['title'] = "$account->name all schedules";
		$data['doc_id'] = $doc_id;
		$data['month'] = $month;
		$data['year'] = $year;
		$data['account'] = $account;

		$this->render('admin/doctors/edit_schedule_view', $data);
	}

	function save_schedule()
	{
		$this->load->model('schedule_model');
		$fields = $this->schedule_model->get_from_post();
		$id = $this->input->post('id');

		if(!$fields){ //validation failed
			$data = $this->schedule_model->get_results();
		}
		else{
			$data = $this->schedule_model->save_schedule($fields, $id);
			$data['refresh'] = 1;
		}
		$data['container'] = '.schedule_message';
		echo json_encode($data);
	}

	function remove_schedule()
	{
		$id = $this->input->post('id');
		$this->load->model('schedule_model');
		$this->schedule_model->delete_record($id);
		$data = $this->schedule_model->get_results();	
		echo json_encode($data);		
	}

	function edit_slot($slot_id)
	{
		$this->load->model('schedule_model');
		$data['item'] = $this->schedule_model->get_slot_info($slot_id);
		$this->render('admin/doctors/edit_slot_view', $data);
	}

	function update_slot_status()
	{
		$this->load->model('schedule_model');
		$id = $this->input->post('id');
		$status = $this->input->post('status');
		$this->schedule_model->update_slot_status($id, $status);
		$data['refresh'] = 1;
		echo json_encode($data);
	}

}
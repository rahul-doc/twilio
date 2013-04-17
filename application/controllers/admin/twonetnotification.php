<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class twonetnotification extends CI_Controller {

	//This class/controller does not extend MY_controller because it must not be behind the authentication mechanism, so 2net can call the methods
	//when notifying of new data, without session cookies
 
	public $filter = null;
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('device_data_model', 'dev_sample');
		$this->load->library('Twonet_partner',array("endpoint"=>$this->config->item('twonet_endpoint'),"key"=>$this->config->item('twonet_key'),"secret"=>$this->config->item('twonet_secret')));
	}

	
	public function notify($guid,$track_guid,$trackname,$category,$start_date,$end_date)
	{
		$call=$category."_filtered";
		
		//get data from 2net
		$data_twonet= $this->twonet_partner->$call($guid, $track_guid, $start_date, $end_date);
		$patient_id = $this->twonet_partner->extract_id ($guid);
		
		//categories body, breath and blood follow a comom structure of data
		if ($category =='body'|| $category =='blood' || $category =='breath' ){
			foreach ($data_twonet as $key =>$measurment){
				$values = $measurment[$category];
				foreach ($values as $dimension => $value){
					$this->dev_sample->save_measurment($patient_id,$dimension,date('Y-m-d H:i:s',$measurment['time']),$values[$dimension]);
				}
				
			}
		} else  if ($category =='nutrition'){
			foreach ($data_twonet as $key =>$measurment){
				$this->dev_sample->save_measurment($patient_id,"calories",date('Y-m-d H:i:s',$measurment['time']),$measurment['calories']);
			}
		//TODO: Test nutrition notifications	
		} else if ($category =='activity'){
			$time=$values['startTime'];
			foreach ($values as $dimension => $value){
				$this->dev_sample->save_measurment($patient_id,$dimension,date('Y-m-d H:i:s',$time),$value);
			}
			//TODO: Test activity notifications
		}
		
		
		
	}

	

}
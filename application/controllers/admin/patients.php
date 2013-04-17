<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
 
 
class Patients extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('patients_model', 'model');
		$this->load->model('accounts_model', 'account');
		$this->load->model('device_patient_model', 'dev_patient');
		$this->load->model('devices_model', 'devices');
		$this->load->model('device_data_model', 'dev_sample');
		$this->_set_filter(array('status', 'name'));
		// load 2net access class
		$this->load->library('Twonet_partner',array("endpoint"=>$this->config->item('twonet_endpoint'),"key"=>$this->config->item('twonet_key'),"secret"=>$this->config->item('twonet_secret')));
		
		$this->load->library('pDraw');
		$this->load->library('pData');
		$this->load->library('pImage');
		

	}
	
	public function device_plot($patient_id,$dimension,$Xsize=1200, $Ysize=530 )
	{

		$dev_data = $this->dev_sample->get_device_data($patient_id,$dimension);
		$to_plot=NULL;
		foreach($dev_data as $key => $result){
			$to_plot[]=$result->value;
		}
		$this->pdata->addPoints($to_plot,"series".$dimension);
		$this->pdata->setSerieWeight("series".$dimension,2); 
		$this->pdata->setSerieDescription("series".$dimension,$dimension);
		

		 $this->pdata->setAxisName(0,"Value");
		 
		
		 /* Create a pChart object and associate your dataset */ 
		 $this->pimage = new pImage($Xsize,$Ysize,$this->pdata);
		
		/* Draw the background */
		 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
		 $this->pimage->drawFilledRectangle(0,0,$Xsize,$Ysize,$Settings);
		
		 /* Overlay with a gradient */
		 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
		 $this->pimage->drawGradientArea(0,0,$Xsyze,$Ysize,DIRECTION_VERTICAL,$Settings);
		 $this->pimage->drawGradientArea(0,0,$Xsize,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));
		
		 /* Add a border to the picture */
		 $this->pimage->drawRectangle(0,0,$Xsize-1,$Ysize-1,array("R"=>0,"G"=>0,"B"=>0));
		 /* Choose a nice font */
		 $this->pimage->setFontProperties(array("FontName"=>"/assets/Fonts/verdana.ttf","FontSize"=>8));
		
		 /* Define the boundaries of the graph area */
		 $this->pimage->setGraphArea(60,40,$Xsize-30,$Ysize-30);
		
		 /* Draw the scale, keep everything automatic */ 
		 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
		 $this->pimage->drawScale($scaleSettings); 
		
		 /* Turn on Antialiasing */ 
		 $this->pimage->Antialias = TRUE; 
		
		 
		 //chart
		 $this->pimage->drawSplineChart();
		 $this->pimage->drawPlotChart(array("PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80));

		 //legend
		 $this->pimage->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		 $this->pimage->drawLegend($Xsize-300,10,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>255,"FontG"=>255,"FontB"=>255));

		 //Title
		 $this->pimage->setFontProperties(array("FontName"=>"/assets/Fonts/verdana.ttf","FontSize"=>12,"R"=>255,"G"=>255,"B"=>255));
		 $this->pimage->drawText(50,18,"Device data");
		
		
		 /* Render the picture (choose the best way) */
		 $this->pimage->autoOutput("Mydoc-deviceplot".$patient_id);


	}
	
	public function device_data($patient_id )
	{

		$patient=$this->account->get_record($patient_id);
		$dimensions= $this->dev_sample->get_dimensions($patient_id);
		
		foreach($dimensions as $key =>$dimension){
			$data['items'][$dimension->name] = "index.php?/admin/patients/device_plot/".$patient_id."/".$dimension->name;	
		}
		$data['title'] = "Device data for patient: ".$patient->name;
		

       	$this->render('admin/devices/devices_view_data', $data);
	}

	public function index($offset = 0)
	{
		$data['title'] = "Patients";
        $data['items'] = $this->_get_list($offset);

       	$this->render('admin/patients/patients_view', $data);
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
		$config['base_url'] = admin_url("patients/index/");
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
		$data['list'] = $this->load->view('admin/patients/patients_list', $d, TRUE);
		echo json_encode($data);
	}



	function add()
	{
    	$this->edit(0);
	}

	function edit($id)
	{
		$data['title'] = $id ? "Edit Patient" : "Add Patient";
		$data['account'] = $this->account->get_record($id);
		$data['item'] = $this->model->get_record($id, FALSE);
		$data['assigned_devices'] = $this->dev_patient->get_devices_pat($id);
		$data['available_devices'] = $this->dev_patient->get_dev_excluding($id);
		$this->render('admin/patients/patients_edit', $data);
	}
	
	function assign_devices($new_id,$assigned_devices){
		//add patient to 2net as user
		$twonet_userid = $this->twonet_partner->calculate_guid($new_id);

		//we delete the user and recreate it from scratch each time, as it seems that 2net API can't delete sensor tracks with unregister_* calls!
		
		$result=$this->twonet_partner->user_delete($twonet_userid);
		$result=$this->twonet_partner->user_register($twonet_userid);
		$twonet_res = $result['code'];
		
		//add devices to profile on 2net
		foreach($assigned_devices as $key => $assigned_dev){
			$assigned_dev_record=$this->devices->get_record($assigned_dev);
			$register_call = 'register_'.$assigned_dev_record->maker;
			$this->twonet_partner->$register_call($twonet_userid,$assigned_dev_record->twonetID);

			//check if device was indeed assigned
			$tracks = $this->twonet_partner->user_tracks($twonet_userid);
			$track_guid=$this->twonet_partner->get_track_guid($assigned_dev_record->maker,$tracks);
			if(!$this->twonet_partner->user_has($assigned_dev_record->maker,$tracks)){
				$twonet_res = 0;	
			}
		}

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
				$this->model->save($id);	
				
				//if the device list was updated, submit to 2net
				if ($this->input->post('assigned_hidden')!="unchanged"){
					$device_list=explode("-",$this->input->post('assigned_hidden'));
					$twonet_res = $this->assign_devices($new_id,$device_list);
					$this->dev_patient->save($new_id,$this->input->post('assigned_hidden'));
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
		
		
		
		if(isset($data['success']) &&($twonet_res==1) ){
			set_success($data['success']);
			$data['redirect'] = admin_url('patients');
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

}
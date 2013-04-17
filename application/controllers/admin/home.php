<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends ADMIN_Controller {

	public function index()
	{		
		$data = array();
		$data['title'] = 'Home page';
		$this->render('admin/home_view', $data);				
				
		//redirect(admin_url('press'));
	}

}

/* End of file home.php */
/* Location: ./application/controllers/admin/home.php */

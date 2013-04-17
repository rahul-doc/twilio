<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Admin_Controller {

	public $filter = null;
	public $count = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('news_model', 'model');
		$this->_set_filter(array('status'));
	}

	public function index()
	{
                
		$this->load->library('messagesender');
                $date = new DateTime();
                $date=$date->format("Y-m-d");
                $this->db->select('adm.email as adminEmail,adm.first_name as admFirst,adm.last_name as admLast,nns.id as notiId,acc.id as accId,acc.email as accEmail,acc.name as accName,nns.news_id,n.title as title,n.description as description,n.list_start_date as newsStrt,n.list_end_date as newsEnd')->from('news_notification_setting nns');
                $this->db->join('news n','nns.news_id=n.id');
                $this->db->join('news_email ne','nns.news_id=ne.news_id');
                $this->db->join('accounts acc','ne.profile_id=acc.id');
                $this->db->join('admin adm','n.user_id=adm.id');
                $this->db->where('nns.status','0');
                $dateWhere="(nns.oneday_before='$date') OR (nns.days_before='$date')";
                $this->db->where($dateWhere);
                
                $result =  $this->db->get()->result();
                //send email code
                
                foreach($result as $r) {
                        $html="Start New Event with Title :".$r->title.",<br />with message ".$r->description.",<br />  on date ".date('d M o',strtotime($r->newsStrt))." and end on date ".date('d M o',strtotime($r->newsEnd));
                        $text="Start New Event with Title :".$r->title.",\r\nwith message ".$r->description.",\r\n  on date ".date('d M o',strtotime($r->newsStrt))." and end on date ".date('d M o',strtotime($r->newsEnd));
                        $this->messagesender->SendEmail($r->accEmail, $r->adminEmail, $r->title, $text, $html);
                        $data['status']='1';
                        $this->db->where("id",$r->notiId);
                        $this->db->update("news_notification_setting", $data);
                        
                    }
                exit;
	}

	

}



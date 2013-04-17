<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends Admin_Controller {

	public $filter = null;
	function __construct()
	{
		parent::__construct();
		$this->load->model('groups_model', 'model');
		$this->load->model('accounts_model', 'account');
		$this->_set_filter(array('status', 'name'));
	}

	public function index($offset = 0)
	{
            
            
		$data['title'] = "Send Notifications";
                $DocWhere=array('account_group'=>'doctor');
                $data['docs'] = $this->model->get_options('accounts', 'id', 'name','', '',$DocWhere);
                
                $PatsWhere=array('account_group'=>'patient');
                $data['pats'] = $this->model->get_options('accounts', 'id', 'name','', '',$PatsWhere);
                
                $data['grps'] = $this->model->get_options('groups', 'id', 'name');
                
                
                $this->render('admin/notifications/notification_add', $data);
	}
        
    public function save()
	{
		
            
		$doc=$_POST['doctor'];
                $pat=$_POST['patient'];
                $grp=$_POST['groups'];
                
                $is_email=$_POST['email'];
                $is_sms=$_POST['sms'];
                $is_notification=$_POST['notification'];
                
                $data['mess']=$_POST['message'];
                
                foreach($grp as $g):
                    $sql = "SELECT doctor_id FROM group_doctors WHERE group_id=$g";           
                     $result=   $this->db->query($sql)->result();
                     foreach($result as $r):
                         $grpIds[]=$r->doctor_id;
                         
                     endforeach;
                endforeach;
                
                $result=array();
                if(!empty($doc)){
                    $result =  $doc;
                }
                else
                {   $doc=array();
                    $result =  array_merge($doc,$pat,$grpIds);
                }
                if(!empty($pat)){
                     $result =  $pat;
                }
                else
                {
                    $pat=array();
                    $result =  array_merge($doc,$pat,$grpIds);
                }
                if(!empty($grpIds)){
                    $result =  $grpIds;
                }
                else
                {
                    $grpIds=array();
                    $result =  array_merge($doc,$pat,$grpIds);
                }
                
                
                $result=  array_unique($result);
                $i=0;
                
                foreach($result as $r):
                 
                    $data['item']['data'][$i] = $this->account->get_record($r);
                
                    $id=$data['item']['data'][$i]->id;
                    $type=($data['item']['data'][$i]->account_group=='doctor')?'0':'1';
                    $message= urlencode(str_replace(' ','%20',$data['mess']));
                    
                    if ($this->input->post('is_email')=='1'){
						#echo 'hi' . $data['item']['data'][$i]->email;
                        require_once('ses.php');
                        $ses = new SimpleEmailService(ses_Access_Key, ses_Secret_Key);
                        
                        $m = new SimpleEmailServiceMessage();
                        $m->addTo($data['item']['data'][$i]->email);
                        $m->setFrom('swardi2001@hotmail.com');
                        $m->setSubject('Notification');
                        $m->setMessageFromString($_POST['message']);
                        $m->setSubjectCharset('ISO-8859-1');
                        $m->setMessageCharset('ISO-8859-1');
                        $result=$ses->sendEmail($m);
                        
                        
                    }
                    else if ($this->input->post('is_notification')=='1'){
                        #print "hello {$id}/{$message}/{$type} <br>";
                        $url="https://api.my-doc.com/notification/notify/{$id}/{$message}/{$type}";
                    
                        //cURL Request For SMS notification

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "X-client-identifier:{$_SERVER['REMOTE_ADDR']}",
                            "X-client-platform:Server",
                            "X-client-version:1.00",
                            "X-client-type:Admin",
                            "Accept: application/json",
                        )); 
                        $data['item']['response'][$i]['res']=curl_exec($ch);
                        curl_close($ch);
                        
                    }
                    else if ($this->input->post('is_sms')=='1'){
                        
                    }
                    
                     
                    $i++;
                    
                endforeach;
		
                
                $this->render('admin/notifications/notification_view', $data);
	}

	

}
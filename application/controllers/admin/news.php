<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News extends Admin_Controller {

    public $filter = null;
    public $count = null;

    function __construct() {
        parent::__construct();
        $this->load->model('news_model', 'model');
        $this->_set_filter(array('status'));
    }

    public function index($offset = 0) {
        $data['title'] = "Manage News";
        $data['items'] = $this->_get_list($offset);
        $data['count'] = $this->count;
        $this->render('admin/news/news_view', $data);
    }

    private function _get_list($offset) {

        $limit = 15;
        $this->count = $this->model->get_count($this->filter);
        $this->_pagination($this->count, $limit);
        return $this->model->get_items($this->filter, $offset, $limit);
    }

    public function _pagination($count, $limit) {
        $config['base_url'] = admin_url("news/index/");
        $config['total_rows'] = $count;
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['num_links'] = 10;
        $this->load->library('pagination');
        $this->pagination->initialize($config);
    }

    function get_ajax_list() {

        $d['items'] = $this->_get_list(0);

        $data['list'] = $this->load->view('admin/news/news_list', $d, TRUE);
        echo json_encode($data);
    }

    function add() {
        
        $this->edit(0);
    }

    function edit($id) {
        $data['title'] = $id ? "Edit news" : "Add news";
        $data['item'] = $this->model->get_record($id);

        /* is email data */
        $DocWhere = array('account_group' => 'doctor');
        $data['docs'] = $this->model->get_options('accounts', 'id', 'name', '', '', $DocWhere);

        $PatsWhere = array('account_group' => 'patient');
        $data['pats'] = $this->model->get_options('accounts', 'id', 'name', '', '', $PatsWhere);

        $data['grps'] = $this->model->get_options('groups', 'id', 'name');


        $this->render('admin/news/news_edit', $data);
    }

    function save() {
        $this->load->library('messagesender');
        $id = $this->input->post('id');
        $_POST['user_id'] = $this->session->userdata('user')->id;
        $this->model->save($id);
        $data = $this->model->get_results();

        $lastID = $this->model->getLastInserted();

        if ($this->input->post('is_email') == '1') {
            $doc = $_POST['doctor'];
            $pat = $_POST['patient'];
            $grp = $_POST['groups'];


            $title = $_POST['title'];
            $grpIds = array();
            foreach ($grp as $g):
                $this->db->select('doctor_id')->from('group_doctors');
                $this->db->where('group_id', $g);
                $result = $this->db->get()->result();
                foreach ($result as $r):
                    $grpIds[] = $r->doctor_id;
                endforeach;
            endforeach;

            //$result = array();
            $resultToSave = array();

            $result = array();
            if (!empty($doc)) {
                $result = $doc;
            } else {
                $doc = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            if (!empty($pat)) {
                $result = $pat;
            } else {
                $pat = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            if (!empty($grpIds)) {
                $result = $grpIds;
            } else {
                $grpIds = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            $result = array_unique($result);

            foreach ($result as $k => $r) {
                $resultToSave = array('news_id' => $lastID, 'profile_id' => $r);
                $this->db->insert('news_email', $resultToSave);
            }


            // save notification settings

            if ($this->input->post('immediate') == 1) {

                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => 0,
                    'days_before' => 0,
                    'status' => 1,
                );
                $this->db->insert('news_notification_setting', $noti);
                // SEND EMAIL HERE
                foreach ($result as $r) {
                    $this->db->select('email,name')->from('accounts');
                    $this->db->where('id', $r);
                    $result = $this->db->get()->result();

                    $html = "{$result[0]->name},<br />Title :" . $_POST['title'] . ",<br />with message " . $_POST['description'] . ",<br />  on date " . date('d M o', strtotime($_POST['list_start_date'])) . " and end on date " . date('d M o', strtotime($_POST['list_end_date'])) . "<br />Sign-Off: <br />Regards, <br />" . $this->session->userdata('user')->first_name . " " . $this->session->userdata('user')->last_name;
                    $text = "{$result[0]->name},\r\nTitle :" . $_POST['title'] . ",\r\n with message " . $_POST['description'] . ",\r\n  on date " . date('d M o', strtotime($_POST['list_start_date'])) . " and end on date " . date('d M o', strtotime($_POST['list_end_date'])) . "\r\nSign-Off: \r\nRegards, \r\n" . $this->session->userdata('user')->first_name . " " . $this->session->userdata('user')->last_name;
                    $this->messagesender->SendEmail($result[0]->email, $this->session->userdata('user')->email, $title, $text, $html);
                }
                // END EMAIL Code HERE
            }
            if ($this->input->post('onehour_before') == 1) {

                $date = new DateTime(date('H:i'));
                $date->modify("-1 hour");
                $time = $date->format("H:i");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => ($this->input->post('onehour_before') == 1) ? $time : 0,
                    'oneday_before' => 0,
                    'days_before' => 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }
            if ($this->input->post('oneday_before') == 1) {
                $strtDate = $this->input->post('list_start_date');
                $date = new DateTime($strtDate);
                $date->modify("-1 day");
                $OneDay = $date->format("Y-m-d");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => ($this->input->post('oneday_before') == 1) ? $OneDay : 0,
                    'days_before' => 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }
            if ($this->input->post('days_before') == 1) {
                $day = $this->input->post('day_before_text');
                $strtDate = $this->input->post('list_start_date');
                $date = new DateTime($strtDate);
                $date->modify("-$day day");
                $days = $date->format("Y-m-d");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => 0,
                    'days_before' => ($this->input->post('days_before') == 1) ? $days : 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }

            // SEND EMAIL HERE
            /* require_once('ses.php');
              $ses = new SimpleEmailService(ses_Access_Key, ses_Secret_Key);
              foreach($result as $r) {

              $this->db->select('email')->from('accounts');
              $this->db->where('id',$r);
              $result =  $this->db->get()->result();

              $m = new SimpleEmailServiceMessage();
              $m->addTo($result[0]->email);
              $m->setFrom('swardi2001@hotmail.com');
              $m->setSubject($title);
              $m->setMessageFromString($mess);
              $result=$ses->sendEmail($m);
              } */
        } else if ($this->input->post('app') == '1') {

            foreach ($result as $r) {

                $this->db->select('id,name,account_group')->from('accounts');
                $this->db->where('id', $r);
                $result = $this->db->get()->result();
                $type = ($result[0]->account_group == 'doctor') ? '0' : '1';
                $message = urlencode(str_replace(' ', '%20', $_POST['description']));
                $url = "https://api.my-doc.com/notification/notify/{$r}/{$message}/{$type}";

                //cURL Request For SMS notification

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "X-client-identifier:{$_SERVER['REMOTE_ADDR']}",
                    "X-client-platform:Server",
                    "X-client-version:1.00",
                    "X-client-type:Admin",
                    "Accept: application/json",
                ));
                curl_exec($ch);
                curl_close($ch);
            }
        }else if ($this->input->post('sms') == '1') {
        }

        if (isset($data['success'])) {
            set_success($data['success']);
            $data['redirect'] = admin_url('news');
        }
        echo json_encode($data);
    }

    function delete() {
        $id = $this->input->post('id');
        $this->model->delete_record($id);
        $data = $this->model->get_results();
        echo json_encode($data);
    }

    public function upload() {
        $this->load->helper('qqupload');


        $allowedExtensions = array("jpg", "gif", "png", "jpeg");

        $max_upload = (int) (ini_get('upload_max_filesize'));
        $max_post = (int) (ini_get('post_max_size'));
        $memory_limit = (int) (ini_get('memory_limit'));
        //set max size to 5 MB if server allow
        $upload_mb = min($max_upload, $max_post, $memory_limit, 5);
        $sizeLimit = $upload_mb * 1000 * 1000;


        $result = qqupload(TMP, $allowedExtensions, $sizeLimit);

        if (isset($result['success'])) {
            $file = $result['file'];
            $new_file = uniqid() . ".jpg";

            //resize image
            if ($this->_resize($result['filename'], $new_file)) {
                //load S3 library
                $this->load->library('s3');

                //upload thumb image to amazon s3	
                $url = $this->s3->uploadData(TMP . "th_" . $new_file, "news/th_" . $new_file);
                //upload big image to amazon s3
                $big_url = $this->s3->uploadData(TMP . $new_file, "news/" . $new_file); //
                //remove images from tmp
                @unlink($result['filename']);
                @unlink(TMP . "th_" . $new_file);
                @unlink(TMP . $new_file);

                if ($url) {
                    $result['file'] = $url;
                    $result['bigfile'] = $big_url;
                } else {
                    $result['error'] = "amazon s3 error";
                }
            } else {
                $result['error'] = "error on resize image";
            }
        }

        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    private function _resize($orig_name, $new_name) {
        $this->load->library('image_lib');

        //create image thumb
        $config['source_image'] = $orig_name;
        $config['new_image'] = TMP . "th_" . $new_name;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = 240;
        $config['height'] = 160;

        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        //create big image 320x240
        $config['source_image'] = $orig_name;
        $config['new_image'] = TMP . $new_name;
        $config['maintain_ratio'] = FALSE;
        $config['width'] = 480;
        $config['height'] = 320;

        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $this->image_lib->clear();

        return $new_name;
    }

    function resend($id) {



        $data['title'] = 'Resend News/Event';
        $data['item'] = $this->model->get_record($id);

        /* is email data */
        $DocWhere = array('account_group' => 'doctor');
        $data['docs'] = $this->model->get_options('accounts', 'id', 'name', '', '', $DocWhere);

        $PatsWhere = array('account_group' => 'patient');
        $data['pats'] = $this->model->get_options('accounts', 'id', 'name', '', '', $PatsWhere);

        $data['grps'] = $this->model->get_options('groups', 'id', 'name');


        $this->render('admin/news/news_resend', $data);
    }
/* TODO: This function needs refactoring */
    function newresend() {

        $this->load->library('messagesender');

        $id = $this->input->post('id');
        $_POST['user_id']=$this->session->userdata('user')->id;
        $this->model->save($id);
        $data = $this->model->get_results();

        $lastID = $id;
        $Eventdata = $this->model->get_record($lastID);

        if ($this->input->post('is_email') == '1') {
            $doc = $_POST['doctor'];
            $pat = $_POST['patient'];
            $grp = $_POST['groups'];


            $title = $_POST['title'];
            $grpIds = array();
            foreach ($grp as $g):
                $this->db->select('doctor_id')->from('group_doctors');
                $this->db->where('group_id', $g);
                $result = $this->db->get()->result();
                foreach ($result as $r):
                    $grpIds[] = $r->doctor_id;
                endforeach;
            endforeach;

            //$result = array();
            $resultToSave = array();

            $result = array();
            if (!empty($doc)) {
                $result = $doc;
            } else {
                $doc = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            if (!empty($pat)) {
                $result = $pat;
            } else {
                $pat = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            if (!empty($grpIds)) {
                $result = $grpIds;
            } else {
                $grpIds = array();
                $result = array_merge($doc, $pat, $grpIds);
            }
            $result = array_unique($result);

            foreach ($result as $k => $r) {
                $resultToSave = array('news_id' => $lastID, 'profile_id' => $r);
                $this->db->insert('news_email', $resultToSave);
            }


            // save notification settings
            if ($this->input->post('immediate') == 1) {

                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => 0,
                    'days_before' => 0,
                    'status' => 1,
                );
                $this->db->insert('news_notification_setting', $noti);
                // SEND EMAIL HERE
                foreach ($result as $r) {
                    $this->db->select('email,name')->from('accounts');
                    $this->db->where('id', $r);
                    $result = $this->db->get()->result();

                    $html = "{$result[0]->name},<br />Title :" . $_POST['title'] . ",<br />with message " . $_POST['description'] . ",<br />  on date " . date('d M o', strtotime($_POST['list_start_date'])) . " and end on date " . date('d M o', strtotime($_POST['list_end_date'])) . "<br />Sign-Off: <br />Regards, <br />" . $this->session->userdata('user')->first_name . " " . $this->session->userdata('user')->last_name;
                    $text = "{$result[0]->name},\r\nTitle :" . $_POST['title'] . ",\r\n with message " . $_POST['description'] . ",\r\n  on date " . date('d M o', strtotime($_POST['list_start_date'])) . " and end on date " . date('d M o', strtotime($_POST['list_end_date'])) . "\r\nSign-Off: \r\nRegards, \r\n" . $this->session->userdata('user')->first_name . " " . $this->session->userdata('user')->last_name;
                    $this->messagesender->SendEmail($result[0]->email, $this->session->userdata('user')->email, $title, $text, $html);
                }
                // END EMAIL Code HERE
            }
            if ($this->input->post('onehour_before') == 1) {

                $date = new DateTime(date('H:i'));
                $date->modify("-1 hour");
                $time = $date->format("H:i");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => ($this->input->post('onehour_before') == 1) ? $time : 0,
                    'oneday_before' => 0,
                    'days_before' => 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }
            if ($this->input->post('oneday_before') == 1) {
                $strtDate = $this->input->post('list_start_date');
                $date = new DateTime($strtDate);
                $date->modify("-1 day");
                $OneDay = $date->format("Y-m-d");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => ($this->input->post('oneday_before') == 1) ? $OneDay : 0,
                    'days_before' => 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }
            if ($this->input->post('days_before') == 1) {
                $day = $this->input->post('day_before_text');
                $strtDate = $this->input->post('list_start_date');
                $date = new DateTime($strtDate);
                $date->modify("-$day day");
                $days = $date->format("Y-m-d");
                $noti = array(
                    'news_id' => $lastID,
                    'app' => $this->input->post('app'),
                    'email' => $this->input->post('email'),
                    'sms' => $this->input->post('sms'),
                    'phone' => $this->input->post('phone'),
                    'onehour_before' => 0,
                    'oneday_before' => 0,
                    'days_before' => ($this->input->post('days_before') == 1) ? $days : 0,
                    'status' => 0,
                );
                $this->db->insert('news_notification_setting', $noti);
            }
        } else if ($this->input->post('app') == '1') {

            foreach ($result as $r) {

                $this->db->select('id,account_group')->from('accounts');
                $this->db->where('id', $r);
                $result = $this->db->get()->result();
                $type = ($result[0]->account_group == 'doctor') ? '0' : '1';
                $message = urlencode(str_replace(' ', '%20', $_POST['description']));
                $url = "https://api.my-doc.com/notification/notify/{$r}/{$message}/{$type}";

                //cURL Request For SMS notification

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "X-client-identifier:{$_SERVER['REMOTE_ADDR']}",
                    "X-client-platform:Server",
                    "X-client-version:1.00",
                    "X-client-type:Admin",
                    "Accept: application/json",
                ));
                curl_exec($ch);
                curl_close($ch);
            }
        }

        //if(isset($data['success'])){
        set_success($data['success']);
        $data['redirect'] = admin_url('news');
        //}
        echo json_encode($data);
    }

}


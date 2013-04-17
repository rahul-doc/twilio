<?php

require('2net.php');

class Twonet_partner extends Twonet {

    const TEST_ENTRA_SERIAL_NUMBER = "2NET00001";
    const TEST_NONIN_SERIAL_NUMBER = "2NET00002";
    const TEST_ANDWS_SERIAL_NUMBER = "2NET00003";
    const TEST_ANDBPM_SERIAL_NUMBER = "2NET00004";
    const TEST_ASTHMAPOLIS_SERIAL_NUMBER = "2NET00005";

    const TRACK_ANDBPM = "andbpm";
    const TRACK_ANDWS = "andws";
    const TRACK_ASTHMAPOLIS = "asthmapolis";
    const TRACK_BODYMEDIA = "bodymedia";
    const TRACK_ENTRA = "entra";
    const TRACK_FITBIT = "fitbit";
    const TRACK_FITLINXX = "fitlinxx";
    const TRACK_MAPMYFITNESS  = "mapmyfitness";
    const TRACK_NONIN = "nonin";
    const TRACK_NOOM = "noom";
    const TRACK_RUNKEEPER = "runkeeper";
    const TRACK_WITHINGS = "withings";

    public function __construct($params) {
        parent::__construct($params['endpoint'], $params['key'], $params['secret']);
    }

    private function twonet_register_with_serial_number($guid, $serial_number) {
        return array('registerRequest' => array('guid' => $guid,
            'credentials' => array('serialNumber' => $serial_number)));
    }

    private function twonet_measure_request($guid, $track_guid) {
        return array('measureRequest' => array('guid' => $guid, 'trackGuid' => $track_guid));
    }

    private function twonet_measure_request_filter($guid, $track_guid, $start_date, $end_date) {
        return array('measureRequest' => array('guid' => $guid,
            'trackGuid' => $track_guid,
            'filter' => array('startDate' => $start_date, 'endDate' => $end_date)));
    }

	function calculate_guid($id) {
        return $id."-MydocApp-".md5($id);
    }
	
    function extract_id($guid) {
        return substr($guid,0,strpos($guid,'-MydocApp'));
    }
    
	function notifications_list() {
        return $this->twonet_post('/partner/notify/list');
    }
    
	function notifications_subscribe($guid,$track,$callback) {
        $register_request = array('registerRequest' => array('guid' => $guid,'trackGuid' => $track,'callbackUrl' => $callback));
        $track_guids_response = $this->twonet_post("/partner/notify/subscribe", $register_request);
        return $track_guids_response['trackGuidsResponse']['status'];
    }
        
    function audit_guids() {
        return $this->twonet_get('/partner/audit/guids');
    }

    function user_register($guid) {
        $register_request = array('registerRequest' => array('guid' => $guid));
        $track_guids_response = $this->twonet_post("/partner/register", $register_request);
        return $track_guids_response['trackGuidsResponse']['status'];
    }

    function user_unregister($guid) {
        $status_response = $this->twonet_delete("/partner/user/delete/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function user_tracks($guid) {
        $track_guids_response = $this->twonet_get("/partner/user/tracks/${guid}");
        return $track_guids_response['trackGuidsResponse'];
    }

    function user_exists($guid) {
        $status_response = $this->twonet_get("/partner/user/exists/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function user_delete($guid) {
        $status_response = $this->twonet_delete("/partner/user/delete/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function user_has($track_name, $tracks) {
        return array_key_exists("${track_name}TrackGuids", $tracks);
    }

    function get_track_guid($track_name, $tracks) {
        if ($this->user_has($track_name, $tracks)) {
            return $tracks["${track_name}TrackGuids"]["guid"];
        }
        return null;
    }

    function register_andbpm($guid, $serial_number) {
        $register_request = $this->twonet_register_with_serial_number($guid, $serial_number);
        $track_guids_response = $this->twonet_post("/partner/register/andbpm", $register_request);
        return $track_guids_response;
    }

    function unregister_andbpm($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/andbpm/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_andws($guid, $serial_number) {
        $register_request = $this->twonet_register_with_serial_number($guid, $serial_number);
        $track_guids_response = $this->twonet_post("/partner/register/andws", $register_request);
        return $track_guids_response;
    }

    function unregister_andws($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/andws/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_asthmapolis($guid, $serial_number) {
        $register_request = $this->twonet_register_with_serial_number($guid, $serial_number);
        $track_guids_response = $this->twonet_post("/partner/register/asthmapolis", $register_request);
        return $track_guids_response;
    }

    function unregister_asthmapolis($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/asthmapolis/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_entra($guid, $serial_number) {
        $register_request = $this->twonet_register_with_serial_number($guid, $serial_number);
        $track_guids_response = $this->twonet_post("/partner/register/entra", $register_request);
        return $track_guids_response;
    }

    function unregister_entra($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/entra/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_nonin($guid, $serial_number) {
        $register_request = $this->twonet_register_with_serial_number($guid, $serial_number);
        $track_guids_response = $this->twonet_post("/partner/register/nonin", $register_request);
        return $track_guids_response;
    }

    function unregister_nonin($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/nonin/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_noom($guid, $email, $access_code) {
        $register_request = array('registerRequest' => array('guid' => $guid,
            'credentials' => array('identity' => $email, 'accessCode' => $access_code)));
        $track_guids_response = $this->twonet_post("/partner/register/noom", $register_request);
        return $track_guids_response;
    }

    function unregister_noom($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/noom/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function register_withings($guid, $email, $password, $nickname) {
        $register_request = array('registerRequest' => array('guid' => $guid,
            'credentials' => array('identity' => $email, 'password' => $password, 'qualifier' => $nickname)));
        $track_guids_response = $this->twonet_post("/partner/register/withings", $register_request);
        return $track_guids_response;
    }

    function unregister_withings($guid) {
        $status_response = $this->twonet_delete("/partner/device/remove/withings/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function authorize_bodymedia($guid) {
        $authorization_response = $this->twonet_get("/partner/device/authorize/bodymedia/${guid}");
        return $authorization_response['authorizationResponse'];
    }

    function deauthorize_bodymedia($guid) {
        $status_response = $this->twonet_delete("/partner/device/deauthorize/bodymedia/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function authorize_fitbit($guid) {
        $authorization_response = $this->twonet_get("/partner/device/authorize/fitbit/${guid}");
        return $authorization_response['authorizationResponse'];
    }

    function deauthorize_fitbit($guid) {
        $status_response = $this->twonet_delete("/partner/device/deauthorize/fitbit/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function authorize_mapmyfitness($guid) {
        $authorization_response = $this->twonet_get("/partner/device/authorize/mapmyfitness/${guid}");
        return $authorization_response['authorizationResponse'];
    }

    function deauthorize_mapmyfitness($guid) {
        $status_response = $this->twonet_delete("/partner/device/deauthorize/mapmyfitness/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function authorize_runkeeper($guid) {
        $authorization_response = $this->twonet_get("/partner/device/authorize/runkeeper/${guid}");
        return $authorization_response['authorizationResponse'];
    }

    function deauthorize_runkeeper($guid) {
        $status_response = $this->twonet_delete("/partner/device/deauthorize/runkeeper/${guid}");
        return $status_response['statusResponse']['status'];
    }

    function blood_latest($guid, $track_guid) {
        $measure_request = $this->twonet_measure_request($guid, $track_guid);
        $measure_response = $this->twonet_post("/partner/measure/blood/latest", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function blood_filtered($guid, $track_guid, $start_date, $end_date) {
        $measure_request = $this->twonet_measure_request_filter($guid, $track_guid, $start_date, $end_date);
        $measure_response = $this->twonet_post("/partner/measure/blood/filtered", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function body_latest($guid, $track_guid) {
        $measure_request = $this->twonet_measure_request($guid, $track_guid);
        $measure_response = $this->twonet_post("/partner/measure/body/latest", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function body_filtered($guid, $track_guid, $start_date, $end_date) {
        $measure_request = $this->twonet_measure_request_filter($guid, $track_guid, $start_date, $end_date);
        $measure_response = $this->twonet_post("/partner/measure/body/filtered", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function breath_latest($guid, $track_guid) {
        $measure_request = $this->twonet_measure_request($guid, $track_guid);
        $measure_response = $this->twonet_post("/partner/measure/breath/latest", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function breath_filtered($guid, $track_guid, $start_date, $end_date) {
        $measure_request = $this->twonet_measure_request_filter($guid, $track_guid, $start_date, $end_date);
        $measure_response = $this->twonet_post("/partner/measure/breath/filtered", $measure_request);
        return $measure_response['measureResponse']['measures']['measure'];
    }

    function activity_latest($guid, $track_guid) {
        $activity_request = array('activityRequest' => array('guid' => $guid, 'trackGuid' => $track_guid));
        $activity_response = $this->twonet_post("/partner/activity/latest", $activity_request);
        return $activity_response['activityResponse']['activities']['activity'];
    }

    function activity_filtered($guid, $track_guid, $start_date, $end_date) {
        $activity_request = array('activityRequest' => array('guid' => $guid,
            'trackGuid' => $track_guid,
            'filter' => array('startDate' => $start_date, 'endDate' => $end_date)));
        $activity_response = $this->twonet_post("/partner/activity/filtered", $activity_request);
        return $activity_response['activityResponse']['activities']['activity'];
    }

    function nutrition_latest($guid, $track_guid) {
        $nutrition_request = array('nutritionRequest' => array('guid' => $guid, 'trackGuid' => $track_guid));
        $nutrition_response = $this->twonet_post("/partner/nutrition/latest", $nutrition_request);
        return $nutrition_response['nutritionResponse']['nutritions']['nutrition'];
    }

    function nutrition_filtered($guid, $track_guid, $start_date, $end_date) {
        $nutrition_request = array('nutritionRequest' => array('guid' => $guid,
            'trackGuid' => $track_guid,
            'filter' => array('startDate' => $start_date, 'endDate' => $end_date)));
        $nutrition_response = $this->twonet_post("/partner/nutrition/filtered", $nutrition_request);
        return $nutrition_response['nutritionResponse']['nutritions']['nutrition'];
    }
}

?>

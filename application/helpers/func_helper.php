<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$ci = & get_instance();
$ci->load->library('session');

function f($name, $default="")
{
	$CI = & get_instance();
	if(isset($CI->filter[$name])){
		return $CI->filter[$name];
	}
	else return $default;
}

function conf($name)
{
	$CI = & get_instance();
	return $CI->config->item($name);
}



function set_error($message)
{
	$ci = & get_instance();
	$ci->session->set_flashdata("error", $message);
}

function get_error()
{
	$ci = & get_instance();
	return $ci->session->flashdata("error");
}

function set_success($message)
{
	$ci = & get_instance();
	$ci->session->set_flashdata("success", $message);
}

function get_success()
{
	$ci = & get_instance();
	return $ci->session->flashdata("success");
}

function show_message()
{
	$error = get_error();
	if($error){
		echo '<div class="alert alert-error"><button type="button" class="close">×</button>'.$error.'</div><br />';
	}
	$success = get_success();
	if($success){
		echo '<div class="alert alert-success"><button type="button" class="close">×</button>'.$success.'</div><br />';
	}
}

function show_info_page($message, $type='error')
{
	$data['type'] = $type;
	$data['message'] = $message;
	$data['view'] = 'site/message_view';

	$ci = & get_instance();
	$ci->load->view('site/layout', $data);
}

function time_ago ($time)
{
	if(!is_numeric($time)){ //not timestamp
		$time = strtotime($time);
	}

    $time = time() - $time; // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}


function get_image($filename)
{
	if(file_exists( $filename ) && !is_dir( $filename )){
		return base_url( $filename );
	}
	else{
		return base_url("assets/img/no-photo.png");
	}
}



function get_per_page_options()
{
	$page_limits = conf('per_page_options');
	$pages =  explode(',', $page_limits);
	return array_combine($pages, $pages);
}
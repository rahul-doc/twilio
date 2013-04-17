<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('fileuploader.php');

function qqupload($directory='media/tmp/',$allowedExtensions = array(), $sizeLimit = "") {

	$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	$result = $uploader->handleUpload($directory);
	

	// to pass data through iframe you will need to encode all html tags
   //	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

   return $result;
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Admin_Controller {

	public function upload_avatar()
	{
		$this->load->helper('qqupload');

		
		$allowedExtensions = array("jpg", "gif", "png", "jpeg");

		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		//set max size to 5 MB if server allow
		$upload_mb = min($max_upload, $max_post, $memory_limit, 5);
		$sizeLimit = $upload_mb*1000*1000; 

		
		$result = qqupload(TMP, $allowedExtensions, $sizeLimit);

	   	if(isset($result['success']))
		{
			$file = $result['file'];	
			$new_file = uniqid().".jpg";

			//resize image
			if($this->_resize($result['filename'], TMP.$new_file))
			{
				//load S3 library
				$this->load->library('s3');							
							
				$url = $this->s3->uploadData(TMP.$new_file, $new_file, 'mydoc-avatar');
				
				@unlink(TMP.$new_file);
				@unlink($result['filename']);

				if($url){
					$result['file'] = $url;
					$result['filename'] = $url;
				}
				else{
					$result['error'] = "amazon s3 error";
				}
				
			}
			else{
				$result['error'] = "error on resize image";
			}
			
		}

	   	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}

	private function _resize($orig_name, $new_name)
	{
		$this->load->library('image_lib');		
	
		//create image thumb
	   	$config['source_image']	= $orig_name;
		$config['new_image']	= $new_name;
		$config['maintain_ratio'] = FALSE;
		$config['width']	= 160;
		$config['height']	= 240;

		$this->image_lib->initialize($config);
		
		if($this->image_lib->resize()){
			$this->image_lib->clear();
			return $new_name;
		}
		//error occurend
		return FALSE;			
	}

}

/* End of file ajax.php */
/* Location: ./application/controllers/admin/ajax.php */

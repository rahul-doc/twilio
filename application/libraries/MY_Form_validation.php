<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	public function get_errors(){
		return $this->_error_array;
	}

	public function db_exists($str, $field){
		return  !$this->is_unique($str, $field);
	}

	public function great_or_equal($str, $field)
	{
		
		if ( ! isset($_POST[$field]))
		{
			return FALSE;
		}

		$val = $_POST[$field];
		
		if($str < $val){
			$this->set_message('great_or_equal', "The $field must be greater or equal");
			return FALSE;
		}
		return TRUE;		
	}

	public function great_than_field($str, $field)
	{
		
		if ( ! isset($_POST[$field]))
		{
			return FALSE;
		}

		$val = $_POST[$field];
		
		if($str <= $val){
			$this->set_message('great_than_field', "The $str must be greater");
			return FALSE;
		}
		return TRUE;		
	}

	public function multiplier($str, $multi)
	{
		$multipliers = explode(',', $multi);
		foreach($multipliers as $multiplier){
			if($str % $multiplier!=0){
				$this->set_message('multiplier', "Must be multiplier of $multi");
				return FALSE;
			}
		}
		return TRUE;
	}


}
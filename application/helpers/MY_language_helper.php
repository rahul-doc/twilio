<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */
if ( ! function_exists('lang'))
{
	$CI =& get_instance();
	$CI->lang->new_keys = array();

	function lang($line, $id = '')
	{
		$CI =& get_instance();
		$key = $line;
		
		if(!isset($CI->lang->language[$line]) && !in_array($line, $CI->lang->new_keys)){
			$CI->lang->new_keys[] = $line;
		}

		$line = $CI->lang->line($line);
		
		//check if value is empty
		if(empty($line))
		{
			//displays key if the there is not a value in language file
			$line = '!'.$key.'!'; 
		}
		
		if ($id != '')
		{
			$line = '<label for="'.$id.'">'.$line."</label>";
		}

		return $line;
	}
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */
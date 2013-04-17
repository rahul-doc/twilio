<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author	Petru Anton, petea2008@yahoo.com
 */

// ------------------------------------------------------------------------


class Bootstrap_menu 
{
	
		
	/**
	 * Constructor - Sets Menu preferences
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct($params = array())
	{
		$CI =& get_instance();
		$CI->load->helper('url');
		
		$this->initialize($params);
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */
	public function initialize($params = array())
	{

		foreach ($params as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}

	public function create($nav, $active)
	{
		$items = array();
		foreach($nav as $uri => $menu){
			$is_active = ($uri==$active);
			$item['active'] = $is_active;
			$item['label'] = is_array($menu) ? $menu['label'] : $menu;

			if(isset($menu['parent']) && $menu['parent']){
				//add a subitem
				$items[$menu['parent']]['items'][$uri] = $item;

				if($is_active){
					//make active parent also
					$items[$menu['parent']]['active'] = true;
				}
			}
			else{
				$items[$uri] = $item;
			}
		}

		//die(print_r($items));
		return $this->_render($items, $active);

	}
	
	
	public function _render($items, $active = NULL, $level=0)
	{
		$ul_class = $level ? "dropdown-menu" : "nav nav-pills";
		

		$out = "<ul class=\"$ul_class\">\n";
		foreach($items as $uri=>$item)
		{
			$active = $a_attr = $carret= $li_class = '';
			$link = site_url($uri);
			if(isset($item['active']) && $item['active']){
				$active = ' active';
			}		
				
			if(isset($item['items'])){
				$a_attr = "class=\"dropdown-toggle\" data-toggle=\"dropdown\"";
				$li_class = "dropdown";				
				$carret = " <b class=\"caret\"></b>";
			}	
			$out .= "<li class=\"$li_class$active\">\n";
			$out .= "\t<a $a_attr href=\"$link\">".$item['label'].$carret."</a>\n";

			if(isset($item['items'])){
				$out .= $this->_render($item['items'], $active, $level+1);
			}
		}
		
		$out .="</li>\n";
		$out .= "</ul>\n";
		return $out;
	}
}
	
	
/* End of file bootstrap_menu.php */
/* Location: ./application/libraries/bootstrap_menu.php */
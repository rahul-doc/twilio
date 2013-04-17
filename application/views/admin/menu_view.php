<?php

$a = trim($this->config->item('admin_path'), "/");
$nav = array(
	//"$a"				=> array('label'=>'Home'),
	"$a/patients"			=> array('label'=>'Patients'),
	//"patients"			=> array('label'=>'Patients'),
	//"$a/patients"		=> array('label'=>'Patient List', 'parent'=>'patients'),
	//"$a/patients/add"	=> array('label'=>'Add Patient', 'parent'=>'patients'),

	"$a/doctors"			=> array('label'=>'Doctors'),
	//"doctors"			=> array('label'=>'Doctors'),
	//"$a/doctors"		=> array('label'=>'Doctor List', 'parent'=>'doctors'),
	//"$a/doctors/add"	=> array('label'=>'Add Doctor', 'parent'=>'doctors'),
	
	"$a/appointments"		=> array('label'=>'Appointment'),
	
	"$a/price_list"			=> array('label'=>'Price List'),

	"$a/records"		=> array('label'=>'Records'),

	"$a/news"				=> array('label'=>'News & Events'),
	//"news"				=> array('label'=>'News & Events'),
	//"$a/news"			=> array('label'=>'News Lists', 'parent'=>'news'),
	//"$a/news/add"		=> array('label'=>'Add News', 'parent'=>'news'),

	"$a/transactions"	=> array('label'=>'Transactions'),
    "$a/devices"	=> array('label'=>'Devices'),
        "$a/groups"	=> array('label'=>'Groups'),
        //doctors groups

	"$a/settings"			=> array('label'=>'Settings'),
	//"settings"			=> array('label'=>'Settings'),
	//"$a/settings"		=> array('label'=>'Configuration', 'parent'=>"settings"),	

        "$a/notifications"           => array('label'=>'Notifications'),
    
        
	"admin_users"		=> array('label'=>'Administrators'),
	"$a/admin_users"	=> array('label'=>'List of Administrators', 'parent'=>'admin_users'),
	"$a/admin_users/add"=> array('label'=>'Add administrator', 	'parent'=>'admin_users'),
	"$a/roles"			=> array('label'=>'Roles', 	'parent'=>'admin_users'),
	"$a/permissions"	=> array('label'=>'User Permissions', 	'parent'=>'admin_users'),
	
	"#account"			=> array('label'=>'Account'),
	"$a/auth/account"	=> array('label'=>'Edit Account', 'parent' => '#account'),
	"$a/auth/logout"	=> array('label'=>'Logout', 'parent' => '#account'),
);

if(isset($this->permissions))
{
	$grant = array(
			"$a/auth/account",
			"$a/auth/logout",
			"$a/home"
		);

	foreach($nav as $key => $data){

		//check no permissions
		if(in_array($key, $grant)){ 
			continue;
		}

		$fkey = str_replace("$a/", '', $key);
		$fkey = trim($fkey, '/');
		if(strpos($fkey, '/')===FALSE){
			$fkey .="/index";
		}
		if(strrpos($fkey, '#')===0){
			continue;
		}

		if(!isset($this->permissions[$fkey])){
			unset($nav[$key]);
		}
	}

}

$uri = $this->uri->segment_array();
$uri =  array_slice($uri, 0, 3);

$active = implode('/', $uri);
if(!$active){ 
	$active = 'admin';
}
?>

<!-- Navbar -->

<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="brand" href="<?=admin_url()?>"><?=conf('site_name')?></a>
      <div class="nav-collapse collapse">	
		<?=  $this->bootstrap_menu->create($nav, $active);?>
     </div>     	
    </div>
  </div>
</div>

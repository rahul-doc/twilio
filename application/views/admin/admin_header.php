	<meta charset="utf-8">
	<title><?=isset($title) ? $title : ''?></title>

	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<link href="<?=asset_url("admin/css/bootstrap.min.css")?>" rel="stylesheet" />
	<link href="<?=asset_url("admin/css/bootstrap-responsive.css")?>" rel="stylesheet" />	
	<link href="<?=asset_url("css/generic.css")?>" rel="stylesheet" />
	<style type="text/css">body{font-family: 'Ubuntu', sans-serif;}</style>
	

	<script type="text/javascript">
		var index_url = "<?=trim(site_url(),"/")."/"?>";
		var admin_url = "<?=trim(admin_url(), "/")."/" ?>";
		var base_url = "<?=base_url()?>";
		var crsf = "<?=$this->security->get_csrf_hash()?>";
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="'+base_url+'assets/admin/js/jquery-1.8.2.min.js"><\/script>')</script>
	

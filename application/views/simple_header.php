<html>
<head>
<title><?= $title ?></title>
  <script type="text/javascript" src="/dg_assets/js/jquery-1.3.2.min.js"></script>
  <script type="text/javascript" src="/dg_assets/js/jquery-ui-1.7.1.custom.min.js"></script>
 <!-- <script type="text/javascript" src="/dg_assets/js/date.js"></script> -->
  <script type="text/javascript" src="/dg_assets/js/jquery.clockpick.1.2.4.pack.js"></script>
  <script type="text/javascript" src="/dg_assets/js/jquery.tablesorter.min.js"></script>
  <script type="text/javascript" src="/dg_assets/js/jquery.metadata.min.js"></script>
  <script type="text/javascript" src="/dg_assets/js/jquery.validate.pack.js"></script>
  <script type="text/javascript" src="/dg_assets/js/local.js"></script>
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/main_nav.css" />
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/common.css" />
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/smoothness/jquery-ui-1.7.1.custom.css" />
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/clockpick.1.2.4.css" />
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/buttons.css" />
  <link type="text/css" rel="stylesheet" href="/dg_assets/css/tablesorter_blue.css" />
  
  <style type="text/css">

	#work_order{
	  width: <?= 72 * (8.5/2) ?>px;
	  height: <?= 72 * (11.5/2) ?>px;
	}
	
	html{
	  max-width: 1200px;
	  margin: 0 auto;
	}

  </style>

	<script type="text/javascript">
		function keepalive(){ $.get("<?=site_url("Util/keepalive")?>") }
		setInterval( "keepalive()", 10*60*1000 )
	</script>

	<? if($this->session->userdata("user_id") && !$this->session->userdata('is_employee')) : ?>
		<script type="text/javascript">
			$( function(){ $('input[type=submit], button[type=submit]').attr("disabled", 'disabled') })
		</script>
	<? endif ?>

</head>

<body id="<?=isset($body_id) ? $body_id : '' ?>">

<?
$ci = &get_instance();
if($ci->session->userdata('logged_in')) : ?>
	<table style="width:100%">
		<tr>
			<td style="text-align:right; font-size:0.7em;">
				Logged in as <?= $ci->session->userdata('user_name') ?> | 
				<?=anchor("Auth/logout", 'logout') ?>
			</td>
		</tr>
	</table>
<? endif ?>

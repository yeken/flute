<!--<!DOCTYPE HTML>
<html>
<head>
<title><?= $page_title ? $page_title : ".: Panel Administrativo :."?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<script src="<?=base_url()?>assets_be/js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_be/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_be/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_be/js/jquery.tableTools.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/jquery.history.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/ajax.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/jquery.form.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="<?=base_url().'assets_common/js/anytime.js'?>"></script>

<link href="<?=base_url()?>assets_be/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/red_buttons/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/black_buttons/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/general.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_common/css/anytime.css" rel="stylesheet" type="text/css" media="screen" />

<?
if(is_array($scripts))
{
   foreach($scripts AS $script)
   {
      echo "<script type=\"text/javascript\" src=\"".$script."\" ></script>";
   }

}
if(is_array($css))
{
    foreach($css as $stylesheet)
    {
       echo '<link href="'.$stylesheet.'" rel="stylesheet" type="text/css">';
    }
}
?>


</head>
<body>-->
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?= $page_title ? $page_title : ".: Panel Administrativo :."?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?=base_url().'assets_be/css/bootstrap.min.css';?>" media="screen">
    <link rel="stylesheet" href="<?=base_url().'assets_be/css/admin.css';?>" media="screen">
    <script src="<?=base_url().'assets_be/js/jquery.min.js';?>"></script>
	<script src="<?=base_url()?>assets_be/js/jquery-migrate-1.2.1.js" type="text/javascript"></script>
    <script src="<?=base_url().'assets_be/js/bootstrap.min.js';?>"></script>
	<script src="<?=base_url()?>assets_be/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/jquery.tableTools.js" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="<?=base_url().'assets_common/js/anytime.js'?>"></script>
	<script src="<?=base_url()?>assets_common/js/jquery.history.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_common/js/ajax.js" type="text/javascript"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/bootstrap/assets/js/html5shiv.js"></script>
      <script src="../bower_components/bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
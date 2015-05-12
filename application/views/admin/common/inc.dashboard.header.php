<!DOCTYPE HTML>
<html>
<head>
<title><?= $page_title ? $page_title : ".: Panel Administrativo :."?></title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<script src="<?=base_url()?>assets_be/js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_be/js/jquery-ui-1.8.17.custom.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/jquery.history.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/ajax.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets_common/js/jquery.form.js" type="text/javascript"></script>

<link href="<?=base_url()?>assets_be/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/red_buttons/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/black_buttons/jquery.ui.all.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=base_url()?>assets_be/css/general.css" rel="stylesheet" type="text/css" media="screen" />

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

<script type="text/javascript" src="<?=base_url().'assets_common/js/gmaps.js'?>"></script>

<script type="text/javascript" language="javascript">


// For Jquery UI buttons and Datatables
$(document).ready(function () {

	$( ".ajax_button, .button" ).button();

});

</script>

</head>
<body>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?= $page_title ? $page_title : ".: Panel Administrativo :."?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="<?=base_url()?>assets_be/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?=base_url()?>assets_be/css/font-awesome.min.css" rel="stylesheet" media="screen">
    <link href="<?=base_url()?>assets_be/css/admin.css" rel="stylesheet" media="screen">
    <link href="<?=base_url()?>assets_be/css/demo_table.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?=base_url()?>assets_be/css/TableTools.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?=base_url()?>assets_be/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?=base_url()?>assets_be/css/summernote.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?=base_url()?>assets_be/css/summernote-bs3.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?=base_url()?>assets_be/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="screen">
    <link href="<?=base_url()?>assets_common/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets_be/js/multiselect/css/multi-select.css" rel="stylesheet">
    <link href="<?=base_url()?>assets_be/css/simple-sidebar.css" rel="stylesheet">
	<script src="<?=base_url()?>assets_be/js/jquery.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_be/js/jquery-migrate-1.2.1.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/common.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/bootstrap.min.js"></script>
	<script src="<?=base_url()?>assets_be/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_be/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/jquery.tableTools.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_common/js/anytime.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_common/js/jquery.history.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_common/js/ajax.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_common/js/jquery.form.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_common/js/moment.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_be/js/jquery.jeditable.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/jquery.dataTables.editable.js"type="text/javascript"></script>
	<script src="<?=base_url()?>assets_common/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_common/js/datetimepicker-es.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_common/js/typeahead.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>assets_be/js/summernote.js" type="text/javascript"></script>
    <script src="<?=base_url()?>assets_be/js/multiselect/js/jquery.multi-select.js" type="text/javascript"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../bower_components/bootstrap/assets/js/html5shiv.js"></script>
      <script src="../bower_components/bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
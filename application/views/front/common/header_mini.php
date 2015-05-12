<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?=$page_title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$page_description?>">
    <meta name="keywords" content="<?=$page_keywords?>">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?=base_url()?>assets_fe/ico/favicon.png">

    <link href="<?=$link_url?>assets_fe/css/bootstrap-theme.css" rel="stylesheet">
    <link href="<?=$link_url?>assets_fe/css/bootstrap.css" rel="stylesheet">
    <link href="<?=$link_url?>assets_fe/css/fonts.css" rel="stylesheet">
    <link href="<?=$link_url?>assets_fe/css/spinner.css" rel="stylesheet">
    <link href="<?=$link_url?>assets_fe/css/general.css" rel="stylesheet">
    <?
	if($this->company_model->bg_image)
	{
		?><style>
        body
		{
		background: #010D27 url('<?= $this->company_model->bg_image?>') top center repeat-y;
		}
        </style><?	
	}
	if($this->company_model->colors)
	{
		echo "<style>".$this->company_model->colors."</style>";	
	}
	?>
  </head>
  <body>
    <div class="header">
      <div class="container">
        <div class="row">
          <div class="col-xs-12" align="center">
            <?
			if($this->company_model->fantasy_logo)
			{
				?>
				<a href="<?=$link_url?>">
					<img src="<?=$this->company_model->fantasy_logo?>" border="0" style="margin:10px 0px 5px 0px"/>
				</a>
				<?
			}
			else
			{
				
				if($this->company_model->no_logos)
				{
					?><div style="height:25px"></div><?
				}
				else
				{
					?>
					<a href="<?=$link_url?>">
						<img src="<?=base_url().'assets_fe/img/logo.png'?>" border="0" />
					</a>
				<?
				}
			}
			?>
          </div>
        </div>
      </div>
    </div>
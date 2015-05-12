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
          <div class="col-md-5 col-sm-6 hidden-xs" style="padding-top:10px; padding-bottom:10px">
            <a href="<?=$link_url?>pronosticos">
            	
            	<?
                if($this->company_model->main_image)
				{
					?><img src="<?=$this->company_model->main_image?>" border="0" style="max-width: 350px;" /><?	
				}
				?>
            </a>
          </div>
          <div class="col-xs-12 visible-xs" align="center" style="padding-bottom:10px">
            <a href="<?=$link_url?>pronosticos">
            	
            	<?
                if($this->company_model->main_image)
				{
					?><img src="<?=$this->company_model->main_image?>" border="0" style="max-width: 350px; width:100%" /><?	
				}
				?>
            </a>
          </div>
          <div class="col-md-7 col-sm-6 head-title hidden-xs" style="text-align:right">
			<?
			if($this->company_model->fantasy_logo)
			{
				?>
				<a href="<?= $this->company_model->fantasy_logo_url ? $this->company_model->fantasy_logo_url : $link_url?>" target="_blank">
					<img src="<?=$this->company_model->fantasy_logo?>" border="0" style="margin:10px 0px 5px 0px; width:100%; max-width:400px"/>
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
					?><img src="<?=base_url().'assets_fe/img/liga_bancomer.png'?>" border="0" style="width:90%; vertical-align:bottom; max-width:324px" /><?
				}
			}
			?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target="#menu-items">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>            
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="main-menu">
    	<div class="container">
            <div class="collapse navbar-collapse" id="menu-items">
                <ul class="nav navbar-nav main-navbar navbar-left">
                    <li><a <? if($section=='bet'){?>class="active"<? } ?> href="<?=$link_url?>pronosticos"><?= lang('Pronósticos')?></a></li>
                    <li><a <? if($section=='scores'){?>class="active"<? } ?> href="<?=$link_url?>posiciones"><?= lang('Posiciones')?></a></li>
                 	<?
                    if($this->company_model->wall)
					{
					?>
                    	<li><a  <? if($section=='wall'){?>class="active"<? } ?>href="<?=$link_url?>muro"><?= lang('Muro')?></a></li>
                	<?
					}
					
					if($this->company_model->friends_league)
					{
						?>
						<li class="dropdown">
                        <a class="dropdown-toggle <?= $section == "friends_league" ? "active" : ""?>" href="#" data-toggle="dropdown" ><?= lang("Liga de amigos")?><?= $friends_league_notifications['total'] ? " <span class='badge'>".$friends_league_notifications['total']."</span>" : ""?><strong class="caret"></strong></a>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="<?= $link_url?>crear-liga-de-amigos" class="sum-league"><b><?= strtoupper(lang("CREAR LIGA"))?></b></a></li>
                        <li><a href="<?= $link_url?>unirse-liga-de-amigos" class="sum-league"><b><?= lang("UNIRSE A UNA LIGA")?></b></a></li>
						<?
						if(is_array($friends_league))
						{
							foreach($friends_league as $league)
							{
								?>
								<li><a href="<?= $link_url?>liga-de-amigos/<?= urlencode($league['league_id'])?>/<?= urlencode($league['league'])?>"><?= $league['league']?> <?= $friends_league_notifications[$league['league_id']] ? " <span class='badge pull-right'>".$friends_league_notifications[$league['league_id']]."</span>" : ""?></a></li>
								<?	
							}
						}
						?>
                    </ul>
                </li>
						<?	
					}
					?>
                </ul>
                <ul class="nav navbar-nav main-navbar navbar-right">   
                    <li class="min-link"><a <? if($section=='how-to'){?>class="active"<? } ?> href="<?=$link_url?>como-jugar"><?= lang('Cómo jugar')?></a></li>
                    <?
                    if($this->company_model->badges)
					{
					?>
                    	<li class="min-link"><a  <? if($section=='badges'){?>class="active"<? } ?>href="<?=$link_url?>badges"><?= lang('Badges')?></a></li>
					<?
					}
					?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"><?= $shortname?> <span class="glyphicon glyphicon-user"></span><strong class="caret"></strong></a>
                        <ul class="dropdown-menu" role="menu">
                            <li <? if($section=='usuario'){?>class="active"<? } ?>><a href="<?=$link_url?>mi-cuenta"><?= lang('Mi cuenta')?></a></li>
                    		<li><a href="<?=$link_url?>log-out"><?= lang('Salir')?></a></li>
                        </ul>
                  	</li> 
                   	<?
					if($this->company_model->multi_lang)
					{
					?>
						<li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"><small><?= $this->bitauth->user_language ? $this->bitauth->user_language : $this->company_model->country?></small><strong class="caret"></strong></a>
                        <ul class="dropdown-menu" role="menu">
                           <li><a href="<?= $link_url?>cambiar-idioma/AR">ARGENTINO (AR)</a></li>
                            <li><a href="<?= $link_url?>cambiar-idioma/MX">MEXICANO (MX)</a></li>
                            <li><a href="<?= $link_url?>cambiar-idioma/PR">PORTUGUES (PR)</a></li>
                            <li><a href="<?= $link_url?>cambiar-idioma/US">ENGLISH (US)</a></li>
                        </ul>
                  	</li> 
                        
					<?	
					}
					?>  
                </ul>
            </div>
        </div>
    </div>
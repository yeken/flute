<? include(dirname(__FILE__)."/common/header.php")?>

<div id="section-header" class="container-fluid">
	<div class="container">
    	<div class="row">
        	<div class="col-md-12"><h1><?= lang("Mi Cuenta")?></h1></div>
        </div>
    </div>
</div>

<div id="section-nav" class="container-fluid">
	<div class="container">
    	<div class="row">
        	<div class="col-md-12">
                <ul class="nav navbar-nav navbar-left">
                        <li><a href="<?= $link_url?>mi-cuenta" class="active"><?= lang("Info General")?></a></li>
                        <li><a href="<?= $link_url?>editar-cuenta"><?= lang("Editar")?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container main-content">
	<div class="col-md-12">
		<div class="panel panel-primary">
    		<div class="panel-body">
					<!--
                    <div class="col-md-3 col-sm-3"><img src="<?= $this->user_profile->image_list?>" class="full-width"></div>
                    -->
                    <div class="col-md-12 col-sm-12">
                    		<div class="row" id="profile-info">
                            	<div class="col-md-12"><h1><?= $this->user_profile->username?> <small><?= $this->user_profile->displayname?></small></h1></div>
                                <div class="col-md-3 col-sm-3"><b><?= lang("Nombre Completo")?></b></div>
                                <div class="col-md-9 col-sm-9" id="profile-name"><?= $this->user_profile->fullname?></div>
                            	<div class="clearfix"></div>
                                <div class="col-md-3 col-sm-3"><b><?= lang("Email")?></b></div>
                                <div class="col-md-9 col-sm-9" id="profile-name"><?= $this->user_profile->email?></div>
                            	<div class="clearfix"></div>
                                <?
                                if($this->company_model->bio)
								{
								?>
                                    <div class="col-md-3 col-sm-3"><b><?= lang("Bio")?></b></div>
                                    <div class="col-md-9 col-sm-9" id="profile-bio"><p><?= $this->user_profile->bio?></p></div>
                                    <div class="clearfix"></div>
                            	<?
								}
                                if($this->company_model->branch_league)
								{
								?>
                                    <div class="col-md-3 col-sm-3"><b><?= lang("Sucursal")?></b></div>
                                    <div class="col-md-9 col-sm-9" id="profile-branch"><p><?= $this->user_profile->branch?></p></div>
                                    <div class="clearfix"></div>
                            	<?
								}
								?>
                                <div id="general-league-score">
                                <div class="col-md-3 col-sm-3"><b><?= lang("Liga General")?></b></div>
                                <div class="col-md-9 col-sm-9">-</div>
                                <div class="clearfix"></div>
                                </div>
                                <div class="col-md-12" id="profile-badges">
                                <?
								//$badges = explode("|","3/2|12/2|13/2|18");
                                if(is_array($badges) && $this->company_model->badges)
								{
									foreach($badges as $badge)
									{
										$badge = explode("/",$badge);
										?>
										<div class="col-sm-3 col-xs-3">
                                            <div class="pull-left score-badges">
                                            <img class="media-object" src="<?= base_url()?>assets_fe/img/badges/64x64/<?= $badges_icons[$badge[0]]['icon']?>" title="<?= $badges_icons[$badge[0]]['name']?>" />
                                            <?
                                            if($badge[1] > 1)
                                            {
          	                                  ?><span class="badge badge-success pull-right"><?= $badge[1]?></span><?
                                            }
                                            ?>
                                            
                                            </div>
                                        </div>
										<?	
									}	
								}
								?>
                                <div class="clearfix"></div>
                                </div>
                                <div class="clearfix"></div>
                            	</div>
                    </div>
                    
			</div>
		</div>
	</div>
</div>


<? include(dirname(__FILE__)."/common/footer.php")?>
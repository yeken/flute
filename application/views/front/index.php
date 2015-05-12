<? include(dirname(__FILE__)."/common/header_mini.php")?>
<div class="container">
	<div class="row">
		<div class="<?= $this->company_model->register ? "col-md-offset-2 col-md-8" : "col-md-offset-3 col-md-6 col-sm-offset-2 col-sm-8"?>">
             <div class="panel panel-primary">
                <div class="panel-heading" align="center">
                <?
                if($this->company_model->main_image)
				{
					?><img src="<?=$this->company_model->main_image?>" border="0" /><?	
				}
				?>
                </div>
                <div class="panel-body">   	
                    <div class="row">
                    	<div class="col-md-12" align="center"><h4><?= lang("INGRESO")?></h4>
                        <?
						
						
                        if($this->company_model->first_login)
						{
							?><small><?= lang("first-time-login")?> <?= $this->company_model->username_field?>
                            <?
                            if($this->company_model->first_login_text)
							{
								echo "<br>".$this->company_model->first_login_text;	
							}
							?>
                            </small><?	
						}
						?>
                        <div class="alert alert-danger" id="error" style="display:none"></div>
                        </div>
                    	<div class="<?= $this->company_model->register && !$no_company ? "col-md-6 col-sm-6" : "col-md-12"?>">
                         <?
						if($no_company)
						{
							?><div align="center" style="margin:20px 0px">Debe entrar al link específico de su empresa.<br>EJ: <b>miempresa.fantasyfutbol2014.com</b></div><? 
						}
						else
						{
						 ?>
                            <form method="POST" id="login-form" class="form-signin ajax_form" action="<?=$link_url.'front_user/login'?>" accept-charset="UTF-8">
                                <div class="form-group" style="margin-bottom:15px; text-align:left">
                                <label for="username"><?= $this->company_model->username_field ? $this->company_model->username_field : "usuario"?></label><br><input type="text" id="username" class="form-control" name="username">
                                </div>
                                <div class="form-group" style=" text-align:left">
                                <label for="password"><?= lang("Contraseña")?></label><br>
                                <input type="password" id="password" class="form-control" name="password">
                                </div>
                                <div class="form-group checkbox">
                                    <label>
                                        <input name="remember_me" id="remember_me" type="checkbox" value="remember-me"> <?= lang("Guardar sesión")?>
                                    </label>
                                </div>
                                    <button type="submit" name="submit" class="btn btn-primary btn-block"><?= lang("Ingresar")?></button>
                            </form>
                            <a class="pull-right" href="<?= $link_url?>olvide-mi-clave"><?= lang('olvide-clave')?></a>
                            <?
                            if($this->company_model->tyc_doc)
							{
							 ?><br><a class="pull-right" href="<?= $this->company_model->tyc_doc?>" target="_blank"><?= lang('politica-privacidad')?></a><?	
							}
							?>
                        <?
						}
						?>
                        </div>
                        <?
                        if($this->company_model->register && !$no_company)
						{
						?>
                            <div class="col-md-6 col-sm-6">
                                <h3><?= lang("email-register")?></h3>
                                <form method="POST" id="register-form" class="form-signin ajax_form" action="<?= $link_url.'front_user/register'?>" accept-charset="UTF-8">
                                    <div id="error" style="display:none" class="alert alert-danger"></div>
                                    <?
                                    if(!$this->company_model->register_domain)
									{
										?>
										<div class="form-group" style=" text-align:left">
											<input type="text" id="email" class="form-control" name="email" placeholder="email">
										</div>
                                    <?
									}
									else
									{
									?>
                                        <div class="input-group" style="margin-bottom:25px;">
                                            <input type="text" id="email" class="form-control" name="email" placeholder="email">
                                            <span class="input-group-addon">@<?= $this->company_model->register_domain?></span>
                                        </div>
                                    <?
									}
									?>
                                    <input type="submit" class="btn btn-success btn-block" value="<?= lang("Ingresar")?>">
                                </form>
                            </div>
                		<?
						}
						?>
                    </div>
                    
                </div>
             </div>
		</div>
        <div class="col-md-4">
        		
		</div>
    </div>
</div>
<? include(dirname(__FILE__)."/common/footer.php")?>
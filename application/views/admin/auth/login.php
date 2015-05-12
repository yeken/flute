<? $this->load->view("/admin/common/inc.header.mini.php", $header_data); ?>
<div style="width:100%; height:100%;" class="">
	<div class="container">
				<? 
                if($messages)
                {?>
                    <div class="alert alert-danger">
                        <a class="close" data-dismiss="alert" href="#">×</a><?= implode("<br>",$messages)?>
                    </div><? 
				}?>
                <form method="POST" class="form-signin ajax_form" action="<?=base_url().'admin/user/login'?>" accept-charset="UTF-8">
			        <h2 class="form-signin-heading">
                    <?
                    if($site_config['login_logo'])
					{
						?><img src="<?= base_url()."assets_be/img/".$site_config['login_logo']?>"><?
					}
					else
					{
						?>Log In <small>Admin</small><?	
					}
					?>
                    </h2>
                	<input type="text" id="username" class="form-control span4" name="username" placeholder="Username">
                    <input type="password" id="password" class="form-control span4" name="password" placeholder="Password">
                    	<div class="clearfix"></div>
                        <div class="checkbox">
                            <label>
                            	<input name="remember_me" id="remember_me" type="checkbox" value="remember-me"> Guardar sesión
                    		</label>
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" name="submit" class="btn btn-info btn-block">Login</button>
                </form>
    </div>

</div>
<? $this->load->view("/admin/common/inc.footer.mini.php",$footer_data);?>
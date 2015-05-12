<? $this->load->view("/admin/common/inc.header.php", $header_data); ?>
<script>
	$(function() {
		//$( "input:submit" ).button();
	});
</script>
<div style="width:100%; height:100%;" class="fakewindowcontain">
<? 	if(!empty($error)){ ?>
    
        <div class="alert alert-danger"><a class="close" data-dismiss="alert" href="#">×</a><?=$error; ?></div>
<? 	}
	if($messages)
	{?>
		<div class="alert alert-danger">
			<a class="close" data-dismiss="alert" href="#">×</a><?= implode("<br>",$messages)?>
		</div><? 
	}
	if($success)
	{?>
		 <div class="alert alert-success">
			Ya enviamos un email a <?= $email?> con la nueva clave.<br>
			Una vez hecho el login podrás cambiarla desde el perfil de usuario.<br> <a href="<?= base_url()?>admin">Log In</a>
		</div><?
	}
	else
	{?>        
		<form action="<?=current_url()?>" method="post" id="user-login-form" class="form-signin ajax_form">

			
			<h2>Recuperá tu clave <small>Admin</small></h2>

			  <input type="text" id="edit-username"  placeholder="Email o usuario" name="username" value="" class="form-control">

			<br><br>
			<input type="submit" id="submit" name="login" value="Enviar" class="btn btn-info btn-block">

		</form> <?

	}?>    
    </div>
</div>

<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>
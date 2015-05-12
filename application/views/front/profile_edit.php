<? include(dirname(__FILE__)."/common/header.php")?>

<div id="section-header" class="container-fluid">
	<div class="container">
    	<div class="row">
        	<div class="col-md-12"><h1>Mi Cuenta</h1></div>
        </div>
    </div>
</div>
<div id="section-nav" class="container-fluid">
	<div class="container">
    	<div class="row">
        	<div class="col-md-12">
                <ul class="nav navbar-nav navbar-left">
                        <li><a href="<?= $link_url?>mi-cuenta">Info General</a></li>
                        <li><a href="<?= $link_url?>editar-cuenta" class="active">Editar</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="container main-content">
	<div class="col-md-12">
		<div class="panel panel-primary">
    		<form class="ajax-form" id="edit-profile-form" method="post" action="<?= $link_url.'home/validate_contact_form/edit_profile'?>">
            <div class="panel-body">
                <div class="col-md-12 col-sm-12">
                        <div class="row" id="profile-info">
                            <div class="col-md-12"><h1><small><?= $this->user_profile->fullname?></small></h1></div>
                            <div class="clearfix"></div>
                             <?
                            $this->admin_forms->label_in_input = false;
                            foreach($page_fields['edit_profile'] as $field => $attr)
                            {
								
								echo '<div class="form-group contact" id="'.$field.'_box">
												'.$this->admin_forms->input_block($field,$attr, $this->user_profile->$field).'
												<div class="clearfix"></div>
												<div id="contact_error_'.$field.'" class="contact_msg_error alert alert-danger" style="display:none"></div>
												<div class="clearfix"></div>
											</div>';

                            }?>
                            <div class="clearfix"></div>
                        </div>
                </div>
                <div class="clearfix"></div>
                <input class="btn btn-primary btn-block" type="submit" value="<?= lang("Guardar cambios")?>">
			</div>
            </form>
		</div>
	</div>
</div>


<? include(dirname(__FILE__)."/common/footer.php")?>

<div class="panel panel-default">

  <div class="panel-content">

    <div id="form_errors" class="alert alert-danger alert-dismissable" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	  <p class="form-errors"><div id="form_errors_message"></div></p>
    </div>
    <form class="form-horizontal ajax_form" style="margin:5px" id="form_1" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
    	<fieldset>
            <div class="btn-group btn-group-justified" style="margin-bottom:20px">
                <a href="#db_configurator/create_base" class="btn btn-primary ajax-button">Crear base de datos</a>
            </div>            
            <div class="btn-group btn-group-justified">
                <a href="#db_configurator/update_base" class="btn btn-primary ajax-button">Actualizar base de datos</a>
            </div>

        </fieldset>
    </form>
</div>
</div>
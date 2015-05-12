<style type="text/css">
.tree_container{

}
</style>

<script type="text/javascript" language="javascript">

$(document).ready(function () {

	$('.datetimepicker').datetimepicker({
	  language: 'es',
	  pick12HourFormat: false
	});

    $('.summernote').summernote({
        height: 150,
        onImageUpload: function(files, editor, welEditable) {
            sendFile(files[0],editor,welEditable);
        }
    });

	function sendFile(file,editor,welEditable) {
		data = new FormData();
		data.append("file", file);
		$.ajax({
			data: data,
			type: "POST",
			url: "<?=base_url()?>common/upload_editor_image",
			cache: false,
			contentType: false,
			processData: false,
			success: function(url) {
					editor.insertImage(welEditable, url);
			}
		});
	}

	$('.btn-submit').click(function(){
		$('.summernote').each( function() {
			$(this).val($(this).code());
		});
		$('.btn-group > .active > input').prop('checked', true);
		submit_url = $(this).attr("submit_url");
		$('#form_1').submit()
	});

	var submit_url = '<?=$form_url_success?>';
	$('.ajax_form').ajaxForm({
	// dataType identifies the expected content type of the server response 
        dataType:  'json', 
        // success identifies the function to invoke when the server response 
        // has been received 
		beforeSend: function() {
			var percentVal = '0%';
			$('#loading').html('Cargando '+percentVal);
			loading();
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			$('#loading').html('Cargando '+percentVal);
		},
		complete: function(xhr) {
			stop_loading();
		},	
        success:   validate_ajax_form 
	});

	function validate_ajax_form(data) {
		if(!data.valid)
		{
			$('#form_errors').show();
			$('#form_errors_message').html(data.errors);
		}else{
			$('#form_errors').slideUp();
			notify('Registro almacenado correctamente', 'success', 5);
			 load_ajax(submit_url);
			// $('#tree_container').jstree('refresh',-1);
		}
	}; 
	
	$(".tree_container input:checkbox").click(function(){

		$(this).siblings().find(':checkbox').attr('checked', this.checked);

	});
	
	$(".modify_file").live('click', function(){
		// id = $(this).attr('fieldname');
		$(this).hide();
		$(this).next().show();
	});
	
	current_tag = '';
	repeated = false;
	var last_repeated;
	$('.form_field').each(function(index){
		if($(this).attr('tag'))
		{
			if(current_tag==$(this).attr('tag'))
			{
				repeated = true;
				last_repeated = $(this);
			}
			if(repeated && current_tag!=$(this).attr('tag'))
			{
				current_tag = $(this).attr('tag');
				$(this).parent().append('<div class="form_input '+$(this).attr('tag')+'"></div>');
				repeated = false;
			}
			
			//$(this).find('.form_input').hide();
			$(this).addClass('tag_buttons');
			$('.' + $(this).attr('tag') ).show();
			
			current_tag = $(this).attr('tag');
		}
	});
	
	if(repeated && current_tag)
	{
		current_tag = $(this).attr('tag');
	    last_repeated.parent().append('<div class="form_input '+last_repeated.attr('tag')+'"></div>');
		repeated = false;
	}

	$('.form_field label').click(function(){
		classname = $(this).parent().attr('tag');
		if(classname!=''){
			html_fields = $(this).parent().find('.form_input').html();
		 	$('.'+classname).html(html_fields);
			$('.'+classname).children().show();
			$('.'+classname).css('clear','both').css('padding','10px').css('border','1px solid #dadada').css('margin-left', '5px').css('margin-top', '5px');
		}
	});
});
</script>
<div class="panel panel-default">
  <div class="panel-content">
	<? 
    if($this->main_model->get_id())
    {
        $this->load->view('admin/common/inc.tabs.php');
    } 
    ?>
    <div id="form_errors" class="alert alert-danger alert-dismissable" style="display:none;">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <div id="form_errors_message"></div>
    </div>
    <form class="form-horizontal ajax_form" style="margin:5px" id="form_1" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
    	<fieldset>
            <?
            if($this->main_model->get_id())
            {
                ?><input type="hidden" name="<?= $this->main_model->db_index?>" value="<?= $this->main_model->get_id()?>"><?	
            }
            if($parent_id && !$this->main_model->get_id())
            {
                ?><input type="hidden" name="<?= $this->main_model->parent_db_index?>" value="<?= $parent_id?>"><?	
            }
            
            foreach($page_displays[$current_tab] as  $display => $fields)
            {
                echo "<div class='".$display."'>";
                foreach($fields as $field)
                {
					$attr = $page_fields[$current_tab][$field];
					switch($attr['type'])
					{
						case 'image': $prev_val = $this->main_model->$attr['tag'];
									break;
						default:	$prev_val = isset($this->main_model->$field) ? $this->main_model->$field : $attr['value'];
								break;	
					}
					
					echo '<div class="form-group" tag="'.$attr['tag'].'">';
					echo $this->admin_forms->input_block($field,$attr,$prev_val,$this->main_model->get_field_description($field));
					echo form_error($field);
					echo "<br clear='all'>";
					echo '</div>';
				
				}
                echo "</div>";	
            }
            ?>
            <div class="btn-group btn-group-justified">
                <a class="btn btn-info btn-submit" submit_url="<?=$form_url_success?>">Guardar</a>
                <?
                if($form_url_new)
				{
				?>
                	<a class="btn btn-primary btn-submit" submit_url="<?= $form_url_new?>">Guardar y crear nuevo</a>
            	<?
				}
                if($form_url_list)
				{
				?>
                	<a class="btn btn-primary btn-submit" submit_url="<?= $form_url_list?>">Guardar y volver a la lista</a>
            	<?
				}
				?>
            </div>
        </fieldset>
    </form>
</div>
</div>
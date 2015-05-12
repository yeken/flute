<style>
	.submit_button{ margin-right:50px; }

	#media-form{ padding:20px; }
	#gallery{ list-style:none; float:left; }
	#gallery li{ float:left; margin:10px; }

	.progress { position:relative; width:25%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; float:left; visibility:hidden;margin-bottom:10px; margin-left:10px; color:#333; }
	.bar { background-color: #427990; width:0%; height:20px; border-radius: 3px; }
	.percent { position:absolute; display:inline-block; top:3px; left:48%; }
	
</style>
<script type="text/javascript" language="javascript">

$(document).ready(function() {

	$(".button").button();

	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');

		$('.ajax_form').ajaxForm({
		// dataType identifies the expected content type of the server response 
			dataType:  'json', 
			// success identifies the function to invoke when the server response 
			// has been received 
			beforeSend: function() {
				$('.progress').css('visibility','visible');
				status.empty();
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			complete: function(xhr) {
				
			},	
			success:   validate_ajax_form
		});
	
		$(".delete-file").click(function(){
			if(confirm("borrar el archivo?"))
			{
				$.get($(this).attr('url'));
				$("#"+$(this).attr('ref_id')).addClass("hidden");	
			}
		});
	
		function validate_ajax_form(data) {
			if(!data.valid)
			{
				$('#form_errors').slideUp();
				$('#form_errors_message').html(data.errors);
				$('#form_errors').slideDown();
			}else{
				$('#form_errors').slideUp();
				notify('Registro almacenado correctamente', 'success', 5);
				load_ajax('<?=$form_url_success?>');
			}
		}; 
	

		
		// image deletion function
		function deleteImage( $item, $target ) {
			$item.fadeOut();
			
			$.ajax({
			  url: $target.attr('href'),
			  success: function(){
				alert('Eliminado correctamente');
			  }
			});
			
			
		}

		// image preview function, demonstrating the ui.dialog used as a modal window
		function viewLargerImage( $link ) {
			var src = $link.attr( "href" ),
				title = $link.siblings( "img" ).attr( "alt" );

			var img = $( "<img alt='" + title + "' width='384' height='288' style='display: none; padding: 8px;' />" )
				.attr( "src", src ).appendTo( "body" );
			
			setTimeout(function() {
				img.dialog({
					title: title,
					width: 900,
					modal: true
				});
			}, 1 );

		}

		// resolve the icons behavior with event delegation
		$( "ul.gallery > li" ).click(function( event ) {
			var $item = $( this ),
				$target = $( event.target );

			if ( $target.is( "a.ui-icon-trash" ) ) {
				deleteImage( $item, $target );
			} else if ( $target.is( "a.ui-icon-zoomin" ) ) {
				viewLargerImage( $target );
			}
			return false;
		});
		
		$("#files-table").stupidtable();
	
	
});

</script>
<div class="panel panel-default">
  <div class="panel-content"><?= $title?><? 
	if($this->main_model->get_id())
	{
		$this->load->view('admin/common/inc.tabs.php');
	} 
	?>
    <div id="form_errors" class="alert alert-danger alert-dismissable" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <div id="form_errors_message"></div>
    </div>

	<div>
	
	
	<? 
	foreach($page_fields[$current_tab] as $field => $attr)
	{	
	?>
    <form id="media-form" class="ajax_form" action="<?= $form_action?>" method="post" enctype="multipart/form-data">
		<?
        if($this->bitauth->is_admin())
		{
			if($this->main_model->get_id())
			{
				?><input type="hidden" name="<?= $this->main_model->db_index?>" value="<?= $this->main_model->get_id()?>"><?	
			}
			if($this->file_manager->get_id())
			{
				?><input type="hidden" name="<?= $this->file_manager->db_index?>" value="<?= $this->file_manager->get_id()?>"><?	
			}
			echo '<div class="clearfix"></div><div class="row">';
			echo '<div class="col-md-2 col-sm-4"><b>'.($attr['upload_label'] ? $attr['upload_label'] : "Seleccionar").': &nbsp;&nbsp;</b></div>';
			echo '<div class="col-md-10 col-sm-8"><input type="file" id="'.$field.'" name="'.$field.'" class="form-control"  multiple="multiple"></div>';
			echo '</div>';
			echo form_error($field);
			?>
	
			<?
			if($attr['groups'])
			{
				?>
				<div class="clearfix"></div>
				<div class="row" style="margin-top:10px">
					<div class="col-sm-4 col-md-2">
						<b><?= $attr['groups_label']?>:</b>
					</div>
					<div class="col-sm-8 col-md-10">
						<select name="group">
							<?
							foreach($attr['groups'] as $group)
							{
								?><option value="<?= $group?>"><?= $group?></option><?	
							}
							?>
						</select>
					</div>
				</div>
				<?	
			}
			if($attr['descriptions'])
			{
					echo "<br clear='all'>";
					echo '<div class="form_field" style="width:40% !important;">';
					echo '<label for="file_title">T&iacute;tulo</label>';
					echo "<br clear='all'>";
					echo '<span class="form_input">
							<input id="file_title" class="title" type="text" value="" name="file_title">
							</span>';
					echo '</div>';
					echo "<br clear='all'>";
					echo '<div class="form_field" style="width:40% !important;">';
					echo '<label for="file_description">Descripci&oacute;n</label>';
					echo "<br clear='all'>";
					echo '<span class="form_input">
							<textarea id="file_description" class="title" type="text" value="" name="file_description" class="tinymce"></textarea>
							</span>';
					
					echo '</div>';
					echo "<br clear='all'>";
			}
			?>
			<div class="clearfix"></div>
			<div class="col-md-12" style="margin-bottom:50px;">
				<a class="btn btn-primary pull-right" onclick="$('#media-form').submit()">Subir archivo</a>
			</div>
			<div class="progress">
				<div class="bar"></div >
				<div class="percent">0%</div >
			</div>		
			<div class="clear"></div>
			
		<?
		}
		if($this->file_manager->get_files())
		{
			if(count($this->file_manager->files_list[$field])>0)
			{
				if($attr['type'] == "image")
				{
					?><h3><?=$attr['label'];?></h3>
                    <div class="row"><?	
                    foreach($this->file_manager->files_list[$field] as $file)
                    {
                        ?>
                            <div class="col-xs-6 col-md-3">
                                <span class="thumbnail" style="text-align:center;">
                                    <h5 class="ui-widget-header" style="height:35px; margin-bottom:30px;"><?= $file->file_name?></h5>
                                    <?= $file->get_thumb()?>
                                    <?=$file->title; ?>
                                    <?=$file->description; ?>
                                    <br />
                                    <a href="<?= $file->get_url()?>" title="Agrandar imagen" class="btn btn-primary btn-xs" target="_blank">
                                        <span class="fa fa-search-plus"></span> Agrandar
                                    </a>
                                    <span url="<?= $delete_url.$file->get_id()?>" class="delete-file btn btn-primary btn-xs" ref_id="file_<?= $file->get_id()?>">
                                    	<span class="fa fa-trash-o"></span> Borrar
                                    </span>
                                </span>
                            </div><?	
                    }
                ?></div><?
				}
				else
				{
					?><h4><?=$attr['label'];?></h4>	
					<div class="row">
                    	<table id="files-table" class="table table-condensed table-hover">
                        	<thead>
                                <tr>
                                    <th data-sort="int">Fecha</th>
                                    <th data-sort="string"><?= $attr['groups_label']?></th>
                                    <th data-sort="string">Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                           	<tbody>
							   <?
                               foreach($this->file_manager->files_list[$field] as $file)
                                {
                                    ?>
                                    <tr id="file_<?= $file->get_id()?>">
                                        <td><?= dateFormat($file->date_created,"Y/m/d H:i")?></td>
                                        <td><?= $file->group?></td>
                                        <td><?= $file->file_name?></td>
                                        <td align="center">
                                            <a href="<?= $file->get_url()?>" target="_blank"><span class="fa fa-search-plus"></span></a>
                                            <?
                                            if($this->bitauth->is_admin())
											{
												?><span url="<?= $delete_url.$file->get_id()?>" class="delete-file" ref_id="file_<?= $file->get_id()?>"><span class="fa fa-trash-o"></span></span><?
											}
											?>
                                        </td>
                                    </tr>
                                    <?
                                }
                                ?> 
                        	</tbody>
                        </table>
                    </div>
                
            <?    }
			}
			else
			{
				?>
					<h2>No hay archivos en <?=$attr['label'];?></h2>
				<?	
			}
		}
		?>
		
    </form>
	<? 
	}
	?>
    
	</div>
</div>
</div>
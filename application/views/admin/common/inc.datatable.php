<script>

TableTools.BUTTONS.export_csv = {
    "sAction": "text",
    "sFieldBoundary": "",
    "sFieldSeperator": "\t",
    "sNewLine": "<br>",
    "sToolTip": "",
    "sButtonClass": "DTTT_button_text",
    "sButtonClassHover": "DTTT_button_text_hover",
    "sButtonText": "CSV",
    "mColumns": "all",
    "bHeader": true,
    "bFooter": true,
    "sDiv": "",
    "fnMouseover": null,
    "fnMouseout": null,
    "fnClick": function( nButton, oConfig ) {
    var oParams = this.s.dt.oApi._fnAjaxParameters( this.s.dt );
    var iframe = document.createElement('iframe');
    iframe.style.height = "0px";
    iframe.style.width = "0px";
    iframe.src = oConfig.sUrl+"?"+$.param(oParams);
    document.body.appendChild( iframe );
    },
    "fnSelect": null,
    "fnComplete": null,
    "fnInit": null
};

TableTools.BUTTONS.export_xls = {
    "sAction": "text",
    "sFieldBoundary": "",
    "sFieldSeperator": "\t",
    "sNewLine": "<br>",
    "sToolTip": "",
    "sButtonClass": "DTTT_button_text",
    "sButtonClassHover": "DTTT_button_text_hover",
    "sButtonText": "XLS",
    "mColumns": "all",
    "bHeader": true,
    "bFooter": true,
    "sDiv": "",
    "fnMouseover": null,
    "fnMouseout": null,
    "fnClick": function( nButton, oConfig ) {
      var oParams = this.s.dt.oApi._fnAjaxParameters( this.s.dt );
    var iframe = document.createElement('iframe');
    iframe.style.height = "0px";
    iframe.style.width = "0px";
    iframe.src = oConfig.sUrl+"?"+$.param(oParams);
    document.body.appendChild( iframe );
    },
    "fnSelect": null,
    "fnComplete": null,
    "fnInit": null
};

<?
if(is_array($table_row_details))
{?>
	function fnFormatDetails ( oTable, nTr )
	{
		var aData = oTable.fnGetData( nTr );
		var sOut = '<div class="datatatable_row_details">';
		<?
		foreach($table_row_details as $row_item)
		{
			?>
			sOut += '<div class="item_description"><label><?= $row_item['label']?>:</label> <value>'+aData[<?= $row_item['ao_index']?>]+'</value></div>';
			<?	
		}
		?>
		sOut += '</div>';
		 
		return sOut;
	}		
		
<?
}
?>

$(function() {

	TableTools.BUTTONS.refresh_table = {
		"sAction": "text",
		"sFieldBoundary": "",
		"sFieldSeperator": "\t",
		"sNewLine": "<br>",
		"sToolTip": "",
		"sButtonClass": "DTTT_button_text",
		"sButtonClassHover": "DTTT_button_text_hover",
		"sButtonText": "Refrezcar",
		"mColumns": "all",
		"bHeader": true,
		"bFooter": true,
		"sDiv": "",
		"fnMouseover": null,
		"fnMouseout": null,
		"fnClick": function( nButton, oConfig ) {
				refresh_datatable();
		},
		"fnSelect": null,
		"fnComplete": null,
		"fnInit": null
	};

	var asInitVals = new Array();
<?
if($datatable_details)
{?>
    /*
     * Insert a 'details' column to the table
     */
    var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<img src="<?= base_url()?>assets_be/images/details_open.png">';
    nCloneTd.className = "center";
     
    $('.datatable thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
     
    $('.datatable tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );
	
    $('.datatable tbody td img').live('click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            this.src = "<?= base_url()?>assets_be/images/details_open.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            this.src = "<?= base_url()?>assets_be/images/details_close.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
<?
}
?>

    var oTable = $('.datatable').dataTable( {
		"bStateSave": true,
		"bScrollInfinite": false,
		"bScrollCollapse": true,
		"iScrollLoadGap" : "50",
		"iDisplayLength": "50",
		"bLengthChange": true,
		"sPaginationType": "full_numbers",
		"oLanguage": {"sZeroRecords": "No se encontraron registros"},
		"sZeroRecords": "No se encontraron registros",
		"sScrollY": "900px",
		"aaSorting": [[ <?= $datatable_details ? 1 : 0?>, "desc" ]],
  		"bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?=site_url($controller_name.'/ajax_datatable')?>",
		"sServerMethod": "GET",
		<?= $datatable_details ? '"aoColumnDefs": [{ "bSortable": false, "aTargets": [ 0 ] }],' : ''?>
		<?
		switch($this->datatable_config['type'])
		{
			case 'simple':
							echo '"sDom": \'<"H"lfr>t<"F"ip<\',';
							break;
							
			default:
							echo '"sDom": \'RT<"H"lfr>t<"F"ip<\',';
							break;	
		}
		
		if($parent_id)
		{
			?>"fnServerParams": function ( aoData ) { aoData.push( { "name": "parent_id", "value": "<?= $parent_id?>" } );},<?	
		}
		
		foreach($page_fields['list'] as $field => $attrs)
		{
			switch($attrs['filter'])
			{ 
				case 'date_range_filter':
				?>	
						"fnServerData": function ( sSource, aoData, fnCallback ) {
										/* Add some data to send to the source, and send as 'POST' */
										aoData.push( { "name": 'min_filter_<?= $field?>', "value": $('#min_filter_<?= $field?>').val() } );
										aoData.push( { "name": 'max_filter_<?= $field?>', "value": $('#max_filter_<?= $field?>').val() } );
										$.ajax( {
											"dataType": 'json',
											"type": "GET",
											"url": "<?=site_url($controller_name.'/ajax_datatable')?>",
											"data": aoData,
											"success": fnCallback
										} );
										},
	
				<?
					break;
				default: break;
			}
		}?>		
		
		"oTableTools": { "sSwfPath": "../assets_be/swf/copy_cvs_xls_pdf.swf", "sRowSelect": "single", "aButtons": [
				{
					"sExtends": "export_csv",
					"sButtonText": "<button type='button' class='btn btn-default' title='Exportar CSV'><span class='glyphicon glyphicon-export'></span> .csv</button>",
					"sUrl": "<?=site_url($controller_name.'/export_datatable/csv')?>"
				},
				{
					"sExtends": "export_xls",
					"sButtonText": "<button type='button' class='btn btn-default' title='Exportar XLS'><span class='glyphicon glyphicon-export'></span> .xls</button>",
					"sUrl": "<?=site_url($controller_name.'/export_datatable/xls')?>"
				},
				{
					"sExtends":    "refresh_table",
					"sButtonText": "<button type='button' class='btn btn-primary' title='Refrezcar'><span class='glyphicon glyphicon-refresh' title='refrezcar'></span></button>",
					"sDiv":        "refresh"
				}
			] },
		"bJQueryUI": true,
		"aoColumns": [ <?=$ao_columns?> ]
    } );
	<? 
	foreach($page_fields['list'] as $field => $attrs)
	{
		switch($attrs['filter'])
		{ 
			case 'date_range_filter':
				
			?>	
				$('#min_filter_<?= $field?>').datetimepicker({
				  language: 'es',
				  pick12HourFormat: false
				});
				$('#max_filter_<?= $field?>').datetimepicker({
				  language: 'es',
				  pick12HourFormat: false
				});
				
				$('#submit_filter_<?= $field?>').live('click', function() { oTable.fnDraw();} 
													);
				$('#submit_filter_<?= $field?>').button();
				
				$('#clear_max_filter_<?= $field?>').live('click',function(){
            		$('#max_filter_<?= $field?>').val('');
  
          		} );
				$('#clear_min_filter_<?= $field?>').live('click',function(){
            		$('#min_filter_<?= $field?>').val('');
  
          		} );
				
				<?
				break;
			default: break;
		}
	}
	
	if($list_editable_fields)
	{
		
	?>
oTable.makeEditable({
		sUpdateURL: "<?= base_url()?>admin/<?= $this->class_name?>/edit_in_place",
		"aoColumns": [
		  <? 
		  	if($datatable_details)
			{
				echo "null,";
			}
		  
		  	foreach($page_fields['list'] as $field => $attrs)
			{
				
				if($attrs['list_visibility'] != 'expanded')
				{
					if($attrs['list_editable'])
					{
						switch($attrs['type'])
						{
							case 'select':
											if($attrs['options'])
											{
												$select_data = array();
												foreach($attrs['options'] as $option)
												{
													$select_data[] = "'".$option['value']."':'".$option['label']."'";	
												}
												
												?>
												{
												indicator: 'Guardando...',
												tooltip: 'Doble click para cambiar',
												loadtext: 'cargando...',
												type: 'select',
												onblur: 'submit',
												loadtype: 'GET',
												data: "{<?= implode(",",$select_data)?>}",
												sUpdateURL: "<?= base_url()?>admin/<?= $this->class_name?>/edit_in_place/<?= $field?>",
												},
												<?
											}
							default:
									?>
									{
												indicator: 'Guardando...',
												tooltip: 'Doble click para cambiar',
												loadtext: 'cargando...',
												type: 'text',
												onblur: 'submit',
												loadtype: 'GET',
												sUpdateURL: "<?= base_url()?>admin/<?= $this->class_name?>/edit_in_place/<?= $field?>",
									},
									
									<?
												
						}
					}
					else
					{
						echo "null,";	
					}	
				}
			}
			?>
			]                                  
		  });
	<?	
	}?>


    $("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, $("tfoot input").index(this) );
    } );
     
    /*
     * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
     * the footer
     */
    $("tfoot input").each( function (i) {
        asInitVals[i] = this.value;
    } );
     
    $("tfoot input").focus( function () {
        if ( this.className == "search_init" )
        {
            this.className = "form-control";
            this.value = "";
        }
    } );
     
    $("tfoot input").blur( function (i) {
        if ( this.value == "" )
        {
            this.className = "search_init form-control";
            this.value = asInitVals[$("tfoot input").index(this)];
        }
    } );

	function refresh_datatable(){
		oTable.fnDraw();
	}
	
	$(".tooltip-link").tooltip();
	
});


</script>


<div class="panel panel-default">
  <div class="panel-heading">
<? 		foreach($page_fields['list'] as $field => $attrs)
		{ 
			echo $attrs['filter'] ? showDattatableFilter($field, $attrs) : NULL;
		}
		?>  
  </div>
  <div class="panel-body">
    <div class="table-responsive">
        <table class="datatable">
            <thead>
                <tr>
                    <? foreach($page_fields['list'] as $field => $attrs){ ?>
                    <th><?=$attrs['label']?></th>
                    <? } ?>
                    <? if(is_array($this->datatable_actions)){?>
                    <th>Acciones</th>
                    <? } ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <? foreach($page_fields['list'] as $field => $attrs){ ?>
                    <th><input type="text" name="<?=$field?>" placeholder="<?=$attrs['label']?>" class="search_init form-control" /></th>
                    <? } ?>
                    <? if(is_array($this->datatable_actions)){?>
                    <th>Acciones</th>
                    <? } ?>
                </tr>
            </tfoot>
            <tfoot>
                <tr>
                    <? foreach($page_fields['list'] as $field => $attrs){ ?>
                    <th><?=$attrs['label']?></th>
                    <? } ?>
                    <? if(is_array($this->datatable_actions)){?>
                    <th>&nbsp;</th>
                    <? } ?>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
	</div>
</div>
</div>
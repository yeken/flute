<script>

function refresh_items()
{
	var str = "";
	$(".multiselect option:selected").each(function () {
		str += $(this).val() + ",";
	});

	str = str.slice(0, -1);
	
	$.ajax({
	  type: "POST",
	  url: '<?= $this->data['relation'][$relation_model]['url_to_store'] ?>',
	  data: {
				main_model_id : <?= $this->main_model->get_id() ?>,
				values : str
			}
	});
}

$(function() {

	$(".multiselect").multiselect();

	$('.multiselect').bind('multiselectselected', function(event, options) {
		refresh_items();
	});

	$('.multiselect').bind('multiselectdeselected', function(event, options) {
		refresh_items();
	});
	$( ".multiselect" ).bind( "_moveOptionNode", function(event, ui) {
		
	});


});
</script>
<div class="panel">
    <h1 class="ui-widget-header"><?= $title?><? $this->load->view('admin/common/inc.tabs.php'); ?></h1>
    <div class="panel-content">
    <select id="<?=$relation_model?>" class="multiselect" multiple="multiple" name="<?=$relation_model?>[]">
		<? 
			
			foreach( $items_related as $item1 )
			{
				?>
				<option id="<?=$item1->value?>" value="<?=$item1->value?>" selected="selected" ><?=$item1->label?></option>
				<? 
			}
			
			foreach( $items_not_related as $item2 )
			{
				?>
				<option id="<?=$item2->value?>" value="<?=$item2->value?>" ><?=$item2->label?></option>
				<? 
			}
		?>
    </select>
	
    </div>
</div>
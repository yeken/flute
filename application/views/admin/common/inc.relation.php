<script>
$(function() {

    $('.datatable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?=site_url($controller_name.'/relation_ajax_datatable/'.$relation_model.'/'.$this->main_model->get_id() )?>",
		"sDom": 'R<"H"lfr>t<"F"ip<',
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
        "aoColumns": [ <?=$relation[$relation_model]['ao_columns']?> ]
    } );
});
</script>
<div class="panel">
    <h1 class="ui-widget-header"><?= $title?><? $this->load->view('admin/common/inc.tabs.php'); ?></h1>
    <div class="panel-content">
        <table class="datatable" width="100%">
            <thead>
                <tr>
					<? foreach($relation[$relation_model]['page_fields']['relation_'.$relation_model] as $field => $attrs){ ?>
                    <th><?=$attrs['label']?></th>
                    <? } ?>                </tr>
            </thead>
        </table>
    </div>
</div>
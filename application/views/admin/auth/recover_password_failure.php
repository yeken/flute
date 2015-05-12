<? $this->load->view("/admin/common/inc.header.php", $header_data); ?>
<script>
	$(function() {
		//$( "input:submit" ).button();
	});
</script>
<div style="width:100%; height:100%;" class="fakewindowcontain">

    <div class='mainInfo login_container ui-widget ui-widget-content ui-corner-all' style="">
        <div class="panel_header_no_bg" style="">LOG IN</div>
        <div class="pageTitleBorder"></div>
        <h2>No se puede recuperar el usuario.<br> Por favor contactate con un administrador.<br> Gracias</h2>
        <div class="clear"></div>
        
    </div>
</div>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>

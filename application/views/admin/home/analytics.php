<? $this->load->view("/admin/common/inc.header.php", $header_data); ?>
<? $this->load->view("/admin/common/inc.top.menu.php", $header_data); ?>
	<div id="main_message">
    	<span id="loading"></span>
        <div class="clear"></div>
    	<span id="notification"></span>
    </div>
    <div id="breadcrumb"></div>
	<div id="top_menu_actions"></div>
	<div id="content"></div>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>
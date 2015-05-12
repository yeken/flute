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
        <? 
		if($messages){ ?>
        <div class="ui-widget">
            <div class="ui-state-error ui-corner-all"  style="margin:10px 0px;"> 
                <p><span class="ui-icon ui-icon-alert" style="float: left;"></span>
                <?= implode("<br>",$messages)?></p>
            </div>
        </div>
        <? } ?>
        
       <h2><b>Nombre de usuario:</b> <?= $username?><br>
       <b>Nueva Contrase&ntilde;a:</b> <?= $password?><br>
        </h2>
        <div class="clear"></div>
        <form action="<?=base_url()?>admin/user/login" method="post" id="user-login-form">
        <input type="hidden" id="edit-username" name="username" value="<?= $username?>">
        <input type="hidden" id="edit-password" name="password"  value="<?= $password?>">
        <input type="submit" id="edit-submit" name="login" value="Log in" class="ui-corner-all">
        <label for="remember_me" class="remember_me"><input type="checkbox" id="remember_me" name="remember_me" value="1">Mantener sesi&oacute;n</label>
        </form>
    </div>
</div>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>

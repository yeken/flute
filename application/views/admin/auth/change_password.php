<? $this->load->view("/admin/common/inc.header.php", $header_data); ?>
<h1>Change Password</h1>

<div id="infoMessage"><?= $message;?></div>

<?= form_open( $controller_name."/change_password");?>

      <p>Old Password:<br />
      <?= form_input($old_password);?>
      </p>
      
      <p>New Password:<br />
      <?= form_input($new_password);?>
      </p>
      
      <p>Confirm New Password:<br />
      <?= form_input($new_password_confirm);?>
      </p>
      
      <?= form_input($user_id);?>
      <p><?= form_submit('submit', 'Change');?></p>
      
<?= form_close();?>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>
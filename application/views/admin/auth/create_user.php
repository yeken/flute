<? $this->load->view("/admin/common/inc.header.php", $header_data); ?>
<div class='mainInfo'>

	<h1>Create User</h1>
	<p>Please enter the users information below.</p>
	
	<div id="infoMessage"><?= $message;?></div>
	
    <?= form_open( $controller_name."/create_user");?>
      <p>First Name:<br />
      <?= form_input($first_name);?>
      </p>
      
      <p>Last Name:<br />
      <?= form_input($last_name);?>
      </p>
      
      <p>Company Name:<br />
      <?= form_input($company);?>
      </p>
      
      <p>Email:<br />
      <?= form_input($email);?>
      </p>
      
      <p>Phone:<br />
      <?= form_input($phone1);?>-<?= form_input($phone2);?>-<?= form_input($phone3);?>
      </p>
      
      <p>Password:<br />
      <?= form_input($password);?>
      </p>
      
      <p>Confirm Password:<br />
      <?= form_input($password_confirm);?>
      </p>
      
      
      <p><?= form_submit('submit', 'Create User');?></p>

      
    <?= form_close();?>

</div>
<? $this->load->view("/admin/common/inc.footer.php",$footer_data);?>
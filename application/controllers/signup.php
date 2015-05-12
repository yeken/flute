<?php

class Signup extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->library('simple_captcha');
  }

  public function index() {
    if (!$this->input->post()) {
      echo "<form method='post' action=''>";
      echo "<img src='signup/get_captcha'/><br/><br/>";
      echo "<input type='text' name='captcha_input' value=''/>";
      echo "<input type='submit' value='Submit'/>";
      echo "</form>";
    } else {
      $captcha_input = $this->input->post('captcha_input', TRUE);
      $captcha_text = $this->simple_captcha->get_captcha_text('signup');
      if ($captcha_input == $captcha_text) {
        $out['valid'] = true;
      } else {
		$out['valid'] = false;
      }
    }
	echo json_encode($out);
  }

  public function get_captcha() {
    print_r($this->simple_captcha->draw_captcha('signup'));
  }

}

?>

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['appId']  = '476959975719659'; 
$config['secret'] = '006de29de504e179313947b455d8609e';
$config['fb_channel_url'] = "www.adyouwish.com/channel.php";
$config['fb_redirect_uri'] = "https://www.facebook.com/KiaMotorsArgentina/app_476959975719659";
$config['fb_page'] = 'http://www.facebook.com/KiaMotorsArgentina/';
$config['fb_page_tab_url'] = 'https://www.facebook.com/KiaMotorsArgentina/app_476959975719659';

//https://developers.facebook.com/docs/reference/php/facebook-getLoginUrl/
$config['facebook_login_parameters'] = array(
											'scope' => 'email',
											'display' => 'page'
											);
$config['facebook_site'] = false;
<?php

class Facebook_model extends CI_Model {
	function get_user() {

		$query = $this->facebook->getUser();
		if ($query) {

			$data['valid'] = TRUE;

			$data['facebook_uid'] = $query;

			return $data;

		} else {

			$data['valid'] = FALSE;

			return $data;

		}

	}
	
	function get_user_fields($fields)
	{
		$CI =& get_instance();
		$access_token = $CI->session->userdata('access_token');


		$fb_connect = curl_init();  

		curl_setopt($fb_connect, CURLOPT_URL, 'https://graph.facebook.com/me?access_token='.$access_token.'&fields='.$fields);  
		curl_setopt($fb_connect, CURLOPT_RETURNTRANSFER, 1);  
		$output = curl_exec($fb_connect);
		

		curl_close($fb_connect);  
		$result = json_decode($output);
		if (isset($result->error)) {
			$data['valid'] = FALSE;
			$data['message'] = $result->error->message;
			$data['type'] = $result->error->type;
			$data['code'] = $result->error->code;
			return $data;

		} else {

			$data['valid'] = TRUE;
			$data['data'] = $result;
			return $data;
		}
	}
	
	function get_access_token() {

		$query = $this->facebook->getAccessToken();
		if ($query) {

			$data['valid'] = TRUE;

			$data['access_token'] = $query;

			return $data;

		} else {

			$data['valid'] = FALSE;

			return $data;

		}

	}
	function get_api_secret() {

		$query = $this->facebook->getApiSecret();
		if ($query) {

			$data['valid'] = TRUE;

			$data['api_secret'] = $query;

			return $data;

		} else {

			$data['valid'] = FALSE;
			return $data;
		}

	}
	function get_app_id() {

		$query = $this->facebook->getApiSecret();
		if ($query) {

			$data['valid'] = TRUE;

			$data['app_id'] = $query;

			return $data;

		} else {

			$data['valid'] = FALSE;

			return $data;

		}

	}
	
	function get_logout_url() {

		$query = $this->facebook->getLogoutUrl(array('next' => base_url()));
		if ($query) {
			$data['valid'] = TRUE;
			$data['logout_url'] = $query;
			return $data;

		} else {
			$data['valid'] = FALSE;
			return $data;
		}
	}
	
	function get_signed_request() {

		$query = $this->facebook->getSignedRequest();
		if ($query) {

			$data['valid'] = TRUE;
			$data['signed_request'] = $query;
			return $data;

		} else {
			$data['valid'] = FALSE;
			return $data;
		}

	}
	function set_access_token($access_token) {

		$query = $this->facebook->setAccessToken($access_token);
		if ($query) {

			$data['valid'] = TRUE;

			return $data;

		} else {

			$data['valid'] = FALSE;
			return $data;

		}

	}
	function set_api_secret($app_secret) {

		$query = $this->facebook->setApiSecret($app_secret);
		if ($query) {

			$data['valid'] = TRUE;

			return $data;

		} else {

			$data['valid'] = FALSE;

			return $data;

		}

	}
	function set_app_id($app_id) {

		$query = $this->facebook->setAppId($app_id);
		if ($query) {

			$data['valid'] = TRUE;

			return $data;

		} else {

			$data['valid'] = FALSE;

			return $data;

		}

	}
	//function is formatted for the following

	//https://graph.facebook.com/ID/CONNECTION_TYPE?access_token=123456

	function get_facebook_object($object, $facebook_uid = '', $access_token = '') {

		if(!$facebook_uid)
		{
			$CI =& get_instance();
			$facebook_uid = $CI->session->userdata('facebook_uid');
			
		}
		if(!$access_token)
		{
			if(!$CI)
				$CI =& get_instance();
			
			$access_token = $CI->session->userdata('access_token');
		}
		$fb_connect = curl_init();  

		curl_setopt($fb_connect, CURLOPT_URL, 'https://graph.facebook.com/'.$facebook_uid.'/'.$object.'?access_token='.$access_token);  

		curl_setopt($fb_connect, CURLOPT_RETURNTRANSFER, 1);  

		$output = curl_exec($fb_connect);  

		curl_close($fb_connect);  
		$result = json_decode($output);
		if (isset($result->error)) {
			$data['valid'] = FALSE;
			$data['message'] = $result->error->message;
			$data['type'] = $result->error->type;
			$data['code'] = $result->error->code;
			return $data;

		} else {

			$data['valid'] = TRUE;
			$data['data'] = $result->data;
			return $data;
		}
	}
}
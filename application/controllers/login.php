<?php

class Login extends CI_Controller {

	function index() {
		if($this->session->userdata('uid') !== false) {
			redirect('menu');
		}   

		$this->load->view('login_form', array('message' => 'Please login.'));
	}

	function validate_user() {
		$this->load->library('acmutils');
		$this->load->config('acm_constants', FALSE, FALSE);

		$uid = $this->input->post('user_name');
		$pw = $this->input->post('password');
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));

		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if($ds) {
			$results = ldap_search($ds, $this->config->item('ACM_ADMIN_OU'), "uid=$uid", array("dn"));

			# If you're having difficulty finding yourself, uncomment these two lines and see if you actually get something here!
			#$user = ldap_get_entries($ds, $results);
			#var_dump($user);
			#exit;

			if(ldap_count_entries($ds, $results) <> 1) {
				ldap_unbind($ds);
				$this->load->view('login_form', array('message' => 'Incorrect credentials'));
				return;
			}

			$dn = ldap_get_dn($ds, ldap_first_entry($ds, $results));
			$bind = @ldap_bind($ds, $dn, $pw);

			if($bind) {
				$this->session->set_userdata(array('uid' => $uid));
				ldap_unbind($ds);
				redirect('menu');
			}
			else {
				ldap_unbind($ds);
				$this->load->view('login_form', array('message' => 'Incorrect credentials.'));
				return;
			}
		}#if($ds)
	}#validate_user

	function logout() {
		$this->session->sess_destroy();
		$this->load->view('login_form', array('message' => 'Logout successful!'));
	}

}#class
?>

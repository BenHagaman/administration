<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Maintenance extends CI_Controller {

	function index() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}

		$this->load->library('table');
		$this->table->set_heading('Name', 'ACM ID', 'Expiration Date');
		$this->load->library('acmutils');
		$this->load->config('acm_constants', FALSE, FALSE);
		
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if($ds) {
			$res = ldap_bind($ds, $this->config->item('ACM_LDAP_BIND_DN'), $this->config->item('ACM_LDAP_BIND_PW'));
			$results = ldap_search($ds, $this->config->item('ACM_LDAP_USERS_OU'), "uid=*");
			$accounts = ldap_get_entries($ds, $results);

			foreach($accounts as $account) {
				if(!is_array($account)) {
					continue;
				}
				$actions = anchor("maintenance/confirm_password/" . $account['uid'][0], 'Reset Password');
				$rowclass = 'unknown';
				if(preg_match('/ou=active/', $account['dn']) == 1) {
					$rowclass = 'active';
				}

				if(preg_match('/ou=officers,ou=active/', $account['dn']) == 1) {
					$rowclass = 'officer';
				}

				if(preg_match('/ou=current,ou=officers,ou=active/', $account['dn']) == 1) {
					$rowclass = 'current';
				}

				if(preg_match('/ou=inactive/', $account['dn']) == 1) {
					$rowclass = 'inactive';
				}

				if($rowclass == 'officer' or $rowclass == 'current') {
					$actions = '&nbsp';
				}

				$expire = '';
				if($account['shadowexpire'][0] == -1) {
					$expire = 'never';
					$actions = '&nbsp;';
				}
				else {
					$expire = date_add(date_create('1970-01-01'), new DateInterval("P" . $account['shadowexpire'][0] . "D"))->format('Y-m-d');
				}

				$this->table->add_row(
					array('class' => $rowclass, 'data' => $account['cn'][0]),
					array('class' => $rowclass, 'data' => $account['uid'][0]),
					array('class' => $rowclass, 'data' => $expire),
					array('class' => 'link', 'data' => $actions)
				);
			}
			$this->load->view('maintenance/current');

		}//end ds
		else {
			$data['message'] = "Unable to bind to LDAP server.";
			$this->acmutils->log_message('Unable to bind to LDAP at Renewal::index', 'system');
			$this->load->view('error', $data);
		}
	}//end index

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function confirm_password($id) {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}

		$this->config->load('acm_constants');
		$this->load->library('acmutils');
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));
		$this->load->model('user_model');

		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		$data = array();
	
		if($ds) {
			$res = ldap_bind($ds, $this->config->item('ACM_LDAP_BIND_DN'), $this->config->item('ACM_LDAP_BIND_PW'));
			$results = ldap_search($ds, $this->config->item('ACM_LDAP_USERS_OU'), "uid=$id");
			$entryID = ldap_first_entry($ds, $results);
			$data['dn'] = ldap_get_dn($ds, $entryID);
		}

		$this->load->view('maintenance/reset', $data);
	}

	function finalize_password() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}
		
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="err_msg">','</div>');
		$this->load->library('acmutils');
		$this->load->model('user_model');
		$this->load->library('table');

		if(empty($_POST)) {
			redirect('maintenance');
		}

		if ($this->form_validation->run('password_reset') == false) {
			$data = array('dn' => $this->input->post('dn'));
			$this->load->view('maintenance/reset', $data);
		}
		else {
			$retval = $this->user_model->reset_password($this->input->post('dn'), $this->input->post('newpassword'));

			if($retval) {
				$this->session->set_flashdata('message', "Updated password for " . $this->input->post('dn'));
			}
			else {
				$this->session->set_flashdata('message', "Unable to update password for " . $this->input->post('dn'));
			}

		redirect('maintenance');
		}
	}//finalize_password

}#class
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Renewal extends CI_Controller {

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
				$actions = anchor("renewal/confirm/" . $account['uid'][0], 'Renew') . "&nbsp;&nbsp;" . anchor("renewal/deactivate/" . $account['uid'][0], 'Deactivate');
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
			$this->load->view('renewal/current');

		}//end ds
		else {
			$data['message'] = "Unable to bind to LDAP server.";
			$this->acmutils->log_message('Unable to bind to LDAP at Renewal::index', 'system');
			$this->load->view('error', $data);
		}
	}//end index

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function confirm($id) {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}
    
		$this->config->load('acm_constants');
		$this->load->library('acmutils');
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));
		$this->load->model('user_model');
    $this->load->model('renewal_date_model');
		$this->load->library('table');

		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if($ds) {
			$res = ldap_bind($ds);
			$results = ldap_search($ds, $this->config->item('ACM_LDAP_USERS_OU'), "uid=$id");
			$entryID = ldap_first_entry($ds, $results);

			$useful = array('cn' => 'Name', 'uid' => 'User name', 'telephoneNumber' => 'Phone', 'street' => 'Street', 'l' => 'City', 'st' => 'State', 'postalCode' => 'Zip', 'telephoneNumber' => 'Phone');
			$this->table->set_heading('Field', 'Value');
			$attr_array = ldap_get_attributes($ds, $entryID);
			$attr_array = array_filter(array_keys($attr_array), function($var) { return is_string($var) ? true : false; });

			$dn = ldap_get_dn($ds, $entryID);

			foreach($useful as $key => $value) {
				$arr = array();

				if(in_array($key, $attr_array)) {
					$arr = ldap_get_values($ds, $entryID, $key);
				}
				else {
					$arr[] = '&nbsp;';
				}

				$this->table->add_row($value, $arr[0]);
			}

		}

		$data = array();
    $data['exps'] = array();
    
    $dates = $this->renewal_date_model->get_renewal_dates(); 

    foreach($dates->result() as $row) {
      $data['exps'][$row->human_readable] = $row->ldap_date;
    }

		$data['dn'] = ldap_get_dn($ds, $entryID);
		$this->load->view('renewal/confirm', $data);
	}

	function finalize() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}
		
		if(empty($_POST)) {
			redirect('renewal');
		}

		$this->load->library('acmutils');
		$this->load->model('user_model');
		$retval = $this->user_model->renew_user($this->input->post());

		$date_xp = $this->input->post('exp_date');
	  $date_hr = $this->acmutils->epoch_to_hr_date($date_xp);

		if($retval == TRUE) {
			$this->session->set_flashdata('message', "Set expiration date to $date_hr ($date_xp).");
		}
		else {
			$this->session->set_flashdata('message', "Unable to update expiration date.");
		}

		redirect('renewal');
	}

}#class
?>

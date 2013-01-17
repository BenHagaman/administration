<?php
class User_model extends CI_Model {
	
	var $db_group_name = 'registration';
	var $ddb;

	function __construct() {
		parent::__construct();
		$this->{$this->db_group_name} = $this->load->database($this->db_group_name, TRUE);
		$this->ddb = $this->load->database('default', TRUE);
	}

	function get_pending_snippets(){
		$this->{$this->db_group_name}->select('p.id, p.real_name, p.x500, p.status as off, a.status as app, p.user_name as user_name');
		$this->{$this->db_group_name}->from('pending_users p');
		$this->{$this->db_group_name}->join('approved_users a', 'a.id = p.id', 'left');
		$query = $this->{$this->db_group_name}->get();
		return $query;
	}

	function get_user($id) {
		$this->{$this->db_group_name}->select('real_name as "Real Name", student_id as "Student ID", ucard as "UCard", x500, email as "E-mail", user_name as "User Name"');
		return $this->{$this->db_group_name}->get_where('pending_users', array('id' => $id));
	}


	function move_to_pending($arr_in) {
		$this->load->library('Acmutils');

		$query = $this->{$this->db_group_name}->get_where('pending_users', array('id' => $arr_in['id']));
		$record = $query->result();
		$user = $record[0];
		
		if(empty($record)) {
			$this->_log_message('No ID available at user_model::move_to_pending', 'system');
			return array('str' => 'No ID available!');
		}

		$user->expire_date = $arr_in['shadowExpire'];
		$user->status = "approved";

		$result = $this->{$this->db_group_name}->insert('approved_users', $user);
		if($result === false) {
			$this->acmutils->log_message('Unable to insert record at user_model::move_to_pending', 'system');
			return array('str' => "There was a problem approving the user.  Most likely the user is already approved.");
		}
		else {
			$this->acmutils->log_message("Approved user x500 " . $user->x500 . ": " . $arr_in['notes'], $this->session->userdata('uid'));

			$this->{$this->db_group_name}->where('id', $arr_in['id']);
			$this->{$this->db_group_name}->update('pending_users', array('status' => 'approved'));

			return array('str' => $user->real_name . " approved!");
		}
	}

	function renew_user($arr_in) {

		$attr = array();

		$date_hr              = $this->acmutils->epoch_to_hr_date($arr_in['exp_date']);
		$dn                   = $arr_in['dn'];
		$attr['shadowExpire'] = $arr_in['exp_date'];

		$this->load->library('acmutils');
		$this->load->config('acm_constants', FALSE, FALSE);
		
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if($ds) {
			$res = ldap_bind($ds, $this->config->item('ACM_LDAP_BIND_DN'), $this->config->item('ACM_LDAP_BIND_PW'));
			if($res) {

				$modify_result = ldap_modify($ds, $dn, $attr);
				if($modify_result) {
					$matches = array();
					if(0 < preg_match('/^uid=(.+),ou=inactive,ou=users,(.*)$/', $dn, $matches)) {
						ldap_rename($ds, $dn, "uid=" . $matches[1], "ou=active,ou=users," . $matches[2], true);
					}
					else {
						$this->acmutils->log_message("Failed to renew (move to active) user dn: " . $dn . ". $date_hr", $this->session->userdata('uid'));
						ldap_close($ds);
						return false;
					}
				}
				else {
					$this->acmutils->log_message("Failed to renew (set date) user dn: " . $dn . ". $date_hr", $this->session->userdata('uid'));
					ldap_close($ds);
					return false;
				}

			}//end bind
		}//end ds

		$this->acmutils->log_message("Renewed user dn: " . $dn . ". $date_hr", $this->session->userdata('uid'));

		return $modify_result;
	}//end function

	function reset_password($dn, $passwd) {

		$attr = array();

		$attr['userPassword'] = "{SHA}" . base64_encode(sha1($passwd, true));

		$this->load->library('acmutils');
		$this->load->config('acm_constants', FALSE, FALSE);
		
		$ds = ldap_connect($this->config->item('ACM_LDAP_SERVER'));
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		if($ds) {
			$res = ldap_bind($ds, $this->config->item('ACM_LDAP_BIND_DN'), $this->config->item('ACM_LDAP_BIND_PW'));
			if($res) {

				$modify_result = ldap_modify($ds, $dn, $attr);

				if($modify_result) {
					$this->acmutils->log_message("Reset password for user dn: $dn.", $this->session->userdata('uid'));
					ldap_close($ds);
					return true;
				}
				else {
					$this->acmutils->log_message("Failed to reset password for user dn: $dn.", $this->session->userdata('uid'));
					ldap_close($ds);
					return false;
				}
			}
			else {
				$this->acmutils->log_message("Failed to bind to reset password for user dn: $dn.", $this->session->userdata('uid'));
				return false;
			}//end res
		}
		else {
			$this->acmutils->log_message("Failed to connect to reset password for user dn: $dn.", $this->session->userdata('uid'));
			return false;
		}//end ds

	}//end function
}

?>

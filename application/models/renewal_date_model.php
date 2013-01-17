<?php
class Renewal_date_model extends CI_Model {
	
	var $id;
	var $human_readable;
	var $ldap_date;

	function __construct() {
		parent::__construct();
	}

	function get_renewal_dates() {
		$this->db->select('id, human_readable, ldap_date');
		$this->db->from('renewal_dates');
		$query = $this->db->get();
		return $query;
	}
}

?>

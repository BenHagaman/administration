<?php
class Log_model extends CI_Model {
	
	var $id;
	var $message;
	var $agent;
	var $timestamp;

	function __construct() {
		parent::__construct();
	}

	function get_all_messages() {
		$this->db->select('id, message, agent, timestamp');
		$this->db->from('log');
		$query = $this->db->get();
		return $query;
	}

	function log_message($message, $from) {
		$error = new stdClass();
		$error->message = $message;
		$error->agent = $from;
		$this->db->insert('log', $error);
		return;
	}
}

?>

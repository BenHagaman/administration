<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Log extends CI_Controller {

	function index() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}

		$this->load->library('table');
		$this->table->set_heading('Timestamp', 'Agent', 'Message');
		$this->load->library('acmutils');

		$this->load->model('log_model');
		$rows = $this->log_model->get_all_messages();
		
		foreach($rows->result() as $row) {
			$this->table->add_row(
				array('data' => $row->timestamp),
				array('data' => $row->agent),
				array('data' => $row->message)
			);
		}

		$this->load->view('log/viewall');
	}//end index

}#class
?>

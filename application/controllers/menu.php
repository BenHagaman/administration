<?php
	class Menu extends CI_Controller {

		function index() {
			if($this->session->userdata('uid') === false) {
				$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			}
			else {
				$this->load->view('main_menu');
			}
		}
	}
?>

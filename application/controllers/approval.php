<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Approval extends CI_Controller {

	function index() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}
		$this->load->model('user_model');
		$this->load->library('table');

		$rows = $this->user_model->get_pending_snippets();

		$this->table->set_heading('Name', 'Internet ID', 'ACM ID');

		foreach($rows->result() as $row) {

			$rowclass = 'error';
			$set_actions = 0;
			$actions = '&nbsp;&nbsp;';

			if($row->off == 'pending' and is_null($row->app)) {
				# Registered online, but no action of any kind yet.
				$set_actions = 1;
				$rowclass = 'pending';
			}
			elseif($row->off == 'approved' and $row->app == 'approved') {
				# Approved by officer, but no account created yet.
				$set_actions = 0;
				$rowclass = 'approved';
			}
			elseif($row->off == 'denied' and is_null($row->app)) {
				# Denied by officers, no account created
				$set_actions = 0;
				$rowclass = 'denied';
			}
			elseif($row->off == 'approved' and $row->app == 'created') {
				# Approved by officers, account created
				$set_actions = 0;
				$rowclass ='created';
			}
			elseif($row->off == 'approved' and $row->app == 'failed') {
				# Approved by officers, but account failed to be created
				$set_actions = 0;
				$rowclass = 'error';
			}

			if($set_actions == 1) {
				$actions = anchor('approval/confirm/' . $row->id, 'Confirm');
				$actions .= "&nbsp;&nbsp;";
				$actions .= anchor('approval/deny/'. $row->id, 'Reject');
			}
			
			$x500 = is_null($row->x500) ? '&nbsp;&nbsp;' : $row->x500;

			$this->table->add_row(
				array('class' => $rowclass, 'data' => $row->real_name),
				array('class' => $rowclass, 'data' => $x500),
				array('class' => $rowclass, 'data' => $row->user_name),
				array('class' => 'link', 'data' => $actions)
			);  

#			$this->table->add_row('class' => 'approved',
#				$row->real_name,
#				$row->x500,
#				$actions
#			);
		}
		$this->load->view('approval/pending');
	}

	function confirm($id) {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}

		$this->load->model('user_model');
		$this->load->library('table');
		$this->load->library('Acmutils');
    $this->load->model('renewal_date_model');
		$rows = $this->user_model->get_user($id);

		$this->table->set_heading('Field', 'Value');

    $dates = $this->renewal_date_model->get_renewal_dates(); 

    foreach($dates->result() as $row) {
      $data['exps'][$row->human_readable] = $row->ldap_date;
    }

		$data['id'] = $id;
		foreach($rows->result() as $row) {
			foreach($row as $key => $value) {
				$this->table->add_row($key, form_input(array('name' => $key, 'id' => $key, 'value' => $value, 'maxlength' => 100)));
			}
		}

		$this->load->view('approval/confirm', $data);
	}

	function finalize() {
		if($this->session->userdata('uid') === false) {
			$this->load->view('login_form', array('message' => "You must login to view the requested page."));
			return;
		}
		
		if(empty($_POST)) {
			redirect('approval');
		}

		$this->load->library('acmutils');
		$this->load->model('user_model');

		$ret_code = $this->user_model->move_to_pending($this->input->post());

		$this->session->set_flashdata('message', $ret_code['str']);

		redirect('approval');
	}

}#class
?>

<?php if(! defined('BASEPATH')) { exit('No direct script access allowed'); }

class Acmutils {

	var $ci;

	function __construct() {
		$ci = &get_instance();
	}

	function log_message($message, $from) {
		$ci =& get_instance();
		$ci->load->model('log_model');
		$ci->log_model->log_message($message, $from);
		return;
	}

  function epoch_to_hr_date($exp) {
    return date_add(date_create('1970-01-01'), new DateInterval("P" . $exp . "D"))->format('Y-m-d');
  }
}//end class

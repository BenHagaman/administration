<?php
	$config = array(
		'password_reset' => array(
			array(
				'field' => 'newpassword',
				'label' => 'Password',
				'rules' => 'required|matches[newpassconf]'
			),
			array(
				'field' => 'newpassconf',
				'label' => 'Confirm Password',
				'rules' => 'required'
			)
		)
	)
?>

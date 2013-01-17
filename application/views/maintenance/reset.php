<html>
<head>
<title>ACM @ U of M: Administration</title>
<?php 
echo link_tag('css/administration_form.css'); echo "\r\n";
echo link_tag('css/acm.css'); echo "\r\n"; ?>
</head>
<body>
<?php
	$this->load->view('acm/header');
	$this->load->view('menus/navmenu');
?>
<div id="content">
<div id="main">
<p><h2>Reset Password</h2></p>
<?php 
echo form_open('maintenance/finalize_password');
?>
<input id="dn" type="hidden" name="dn" size="200" value="<?php echo $dn; ?>"/>
<br/>
<fieldset>
	<legend>New Password</legend>

	<div class="field_container">
	<label for="newpassword">Password</label>
	<input id="newpassword" type="password" name="newpassword" size="50" value=""/>
	<?php echo form_error('newpassword'); ?>
	</div>
	<br/>

	<div class="field_container">
	<label for="newpassconf">Confirm</label>
	<input id="newpassconf" type="password" name="newpassconf" size="50" value=""/>
	</div>
	<br/>

	<div class="field_container">
	<label for="notes">Notes</label>
	<input id="notes" type="text" name="notes" size="50" value="Password reset"/>
	</div>
	<br/>
</fieldset>
<br/>
<?php
echo form_submit('submit', 'Submit');
echo "&nbsp;&nbsp;";
echo anchor('maintenance/index', 'Back to current users');
echo form_close();
?>
</div>
</div>
</body>
</html>

<html>
<head>
<title>ACM @ U of M: Administration</title>
<link rel="stylesheet" href="<?php echo base_url()?>css/administration_form.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url()?>css/acm.css" type="text/css" />
</head>
<body>
<h2>ACM Registration Form</h2>
<?php
	$this->load->view('acm/header');
	echo form_open('login/validate_user');
?>
<p><?php echo $message; ?></p>

<fieldset>
	<legend>Sign in</legend>
	<div class="field_container">
	<label for="user_name">User Name <span class="required">*</span></label>
	<input id="user_name" type="text" name="user_name" size="50" value="<?php echo set_value('user_name'); ?>"/>
	<?php echo form_error('user_name'); ?>
	</div>
	<br/>

	<div class="field_container">
	<label for="password">Password <span class="required">*</span></label>
	<input id="password" type="password" name="password" size="50" value=""/>
	<?php echo form_error('password'); ?>
	</div>
	<br/>
</fieldset>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
</form>
</body>
</html>

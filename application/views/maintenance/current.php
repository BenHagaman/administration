<html>
<head>
<title>ACM @ U of M: Administration</title>
<?php 
echo link_tag('css/administration_form.css'); echo "\r\n";
echo link_tag('css/acm.css'); echo "\r\n";
echo link_tag('css/renew.css'); echo "\r\n"; ?>
</head>
<body>
<?php
	$this->load->view('acm/header');
	$this->load->view('menus/navmenu');
?>
<div id="content">
<div id="main">
<?php

$message = $this->session->flashdata('message');
if(!empty($message)) {
	echo "<span id=\"message\">$message</span>";
}
?>

<p><h2>Account Maintenance</h2></p>
<p>Use this page to reset passwords, and other things too!  (later.  maybe.)</p>
<ul>
<li>Accounts in LIGHT BLUE are past officers and in DARK BLUE are current officers and do not expire.</li>
<li>Accounts in ORANGE are inactive accounts.</li>
<li>Accounts in WHITE are active members.</li>
</p>

<?php echo $this->table->generate(); ?>
</div>
</div>
</body>
</html>

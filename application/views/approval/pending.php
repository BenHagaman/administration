<html>
<head>
<title>ACM @ U of M: Administration</title>
<?php 
echo link_tag('css/administration_form.css'); echo "\r\n";
echo link_tag('css/approve.css'); echo "\r\n";
echo link_tag('css/acm.css'); echo "\r\n"; ?>
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
<p><h2>Pending Users</h2></p>
<p>Use this page to approve or reject accounts for new users who registered online.</p>

<?php echo $this->table->generate(); ?>
</div>
</div>
</body>
</html>

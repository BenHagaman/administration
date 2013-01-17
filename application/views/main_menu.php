<html>
<head>
<title>ACM @ U of M: Administration</title>
<?php 
echo link_tag('css/administration_form.css'); echo "\r\n";
echo link_tag('css/acm.css'); echo "\r\n"; ?>
</head>
<body>
<div id="container">
<?php
	$this->load->view('acm/header');
	$this->load->view('menus/navmenu');
?>
<div id="content">
<div id="main">
<p><h2>Welcome</h2></p>
<p>Hi <?php echo $this->session->userdata('uid'); ?>,</p>
<p>This is the ACM administration tool, used to process pending requests from the online registration process, renew members' accounts, and request features.</p>
</div>
</div>
</div>
</body>
</html>

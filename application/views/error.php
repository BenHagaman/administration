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
<p><h2>Welcome</h2></p>
<p>Oops!  Something is extremely wrong!</p>
<p><?php echo $message; ?></p>
</div>
</div>
</body>
</html>

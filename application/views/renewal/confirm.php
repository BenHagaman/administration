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
<p><h2>Renew User</h2></p>
<?php 
echo form_open('renewal/finalize');
echo $this->table->generate();
?>
<input id="dn" type="hidden" name="dn" size="200" value="<?php echo $dn; ?>"/>
<br/>
<fieldset>
	<legend>Officer Notes</legend>

	<div class="field_container">
	<label for="approved_by">Renewed by</label>
	<input id="approved_by", readonly type="text", name="approved_by", size="50", value="<?php echo $this->session->userdata('uid')?>"/>
	</div>
	<br/>

	<div class="field_container">
	<label for="exp_date">Expiration date</label>
	<!--<input id="exp_date_hr", readonly type="text", name="exp_date_hr", size="50", value="<?php echo $exp_hr; ?>"/>-->
	<!--<input id="exp_date", type="hidden", name="exp_date", value="<?php #echo $exp; ?>"/>-->
  <select id="exp_date" name='exp_date'>
    <?php
      foreach($exps as $text => $computed) {
        echo "<option value=\"$computed\">$text</option>";
      }
    ?>
  </select>
	</div>
	<br/>

	<div class="field_container">
	<label for="notes">Notes (payment method, etc...)</label>
	<input id="notes", type="text", name="notes", size="50" value="No notes"/>
	</div>
	<br/>
</fieldset>
<br/>
<?php
echo form_submit('submit', 'Renew');
echo "&nbsp;&nbsp;";
echo anchor('approval/index', 'Back to current users');
echo form_close();
?>
</div>
</div>
</body>
</html>

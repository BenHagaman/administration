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
<p><h2>Approve User</h2></p>
<p>Are you sure you want to approve this user?</p>
<?php 
echo form_open('approval/finalize');
echo form_hidden('id', $id);
echo $this->table->generate();
?>
<br/>
<fieldset>
	<legend>Officer Notes</legend>

	<div class="field_container">
	<label for="approved_by">Approved by</label>
	<input id="approved_by", readonly type="text", name="approved_by", size="50" value="<?php echo $this->session->userdata('uid')?>"/>
	</div>
	<br/>

	<div class="field_container">




	<div class="field_container">
	<label for="shadowExpire">Expiration date</label>
  <select id="shadowExpire" name='shadowExpire'>
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
echo form_submit('submit', 'Approve');
echo "&nbsp;&nbsp;";
echo anchor('approval/index', 'Back to approval');
echo form_close();
?>
</div>
</div>
</body>
</html>

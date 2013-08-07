<h2>Viewing <span class='muted'>#<?php echo $login->id; ?></span></h2>

<p>
	<strong>Username:</strong>
	<?php echo $login->username; ?></p>
<p>
	<strong>Password:</strong>
	<?php echo $login->password; ?></p>

<?php echo Html::anchor('login/edit/'.$login->id, 'Edit'); ?> |
<?php echo Html::anchor('login', 'Back'); ?>
<h2>Viewing User <span class='muted'>#
	<?php echo $user->id; ?></span>
</h2>
<p>
	<strong>Username:</strong>
	<?php echo $user->username; ?>
</p>
<p>
	<strong>Password:</strong>
	<?php echo '****'; ?>
</p>
<?php echo Html::anchor('user/edit/'.$user->id, 'Edit'); ?> |
<?php echo Html::anchor('user', 'Back'); ?>

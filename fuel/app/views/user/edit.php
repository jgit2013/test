<h2>Editing <span class='muted'>User's Password</span></h2>
<br>

<?php echo render('user/_formedit'); ?>
<p>
	<?php echo Html::anchor('user/view/'.$user->id, 'View'); ?> |
	<?php echo Html::anchor('user', 'Back'); ?></p>

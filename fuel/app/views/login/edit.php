<h2>Editing <span class='muted'>Login</span></h2>
<br>

<?php echo render('login/_form'); ?>
<p>
	<?php echo Html::anchor('login/view/'.$login->id, 'View'); ?> |
	<?php echo Html::anchor('login', 'Back'); ?></p>

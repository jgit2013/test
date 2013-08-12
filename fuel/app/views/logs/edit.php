<h2>Editing <span class='muted'>Log</span></h2>
<br>

<?php echo render('logs/_form'); ?>
<p>
	<?php echo Html::anchor('logs/view/'.$log->id, 'View'); ?> |
	<?php echo Html::anchor('logs', 'Back'); ?></p>

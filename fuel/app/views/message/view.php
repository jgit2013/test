<p>
	<strong>Time:</strong>
	<?php echo $message->time; ?>
</p>
<p>
	<strong>User:</strong>
	<?php echo $message->username; ?>
</p>
<p>
	<strong>Title:</strong>
	<?php echo $message->title; ?>
</p>
<p>
	<strong>Message:</strong>
	<?php echo $message->message; ?>
</p>
<?php echo Html::anchor('message/edit/'.$message->id, 'Edit'); ?> |
<?php echo Html::anchor('message', 'Back'); ?>
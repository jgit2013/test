<h2>Viewing <span class='muted'>#<?php echo $message->id; ?></span></h2>

<p>
	<strong>Id:</strong>
	<?php echo $message->id; ?></p>
<p>
	<strong>Title:</strong>
	<?php echo $message->title; ?></p>
<p>
	<strong>Message:</strong>
	<?php echo $message->message; ?></p>

<?php echo Html::anchor('message/edit/'.$message->id, 'Edit'); ?> |
<?php echo Html::anchor('message', 'Back'); ?>
<h2>Editing <span class='muted'>Message</span></h2>
<br>

<?php echo render('admin/_form_edit_message'); ?>

<p>
	<?php echo Html::anchor('admin/view_message/'.$message->id, 'View'); ?> |
	<?php echo Html::anchor('admin', 'Back'); ?>
</p>
<hr>

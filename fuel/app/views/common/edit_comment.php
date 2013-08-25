<?php if (isset($error_message)): ?>
    <h2><?php echo $error_message; ?></h2>
<?php else: ?>
    <h2>Editing <span class='muted'>Comment</span></h2>
<?php endif; ?>
<br>

<?php echo render('common/_form_edit_comment'); ?>
<hr>

<?php if (isset($error_message)): ?>
    <h2><?php echo $error_message; ?></h2>
<?php else: ?>
    <h2>Adding <span class='muted'>Comment</span></h2>
<?php endif; ?>
<br>

<?php echo render('common/_form_add_comment'); ?>
<hr>
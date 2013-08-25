<?php if (isset($error_message)): ?>
    <h2><?php echo $error_message; ?></h2>
<?php else: ?>
    <h2>Adding <span class='muted'>Message</span></h2>
<?php endif; ?>
<br>

<?php echo render('common/_form_add_message'); ?>
<hr>

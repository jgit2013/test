<?php if (isset($error_message)): ?>
    <h2><span class='muted'><?php echo $error_message; ?></span></h2>
<?php else: ?>
    <h2><span class='muted'>Create An User</span></h2>
<?php endif; ?>
<br>

<?php echo render('main/_form_sign_up'); ?>

<hr>

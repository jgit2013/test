<?php if (isset($error_message)): ?>
    <h2><span class='muted'><?php echo $error_message; ?></span></h2>
<?php else: ?>
    <h2><span class='muted'>Admin User: Username=admin, Password=admin</span></h2>
<?php endif; ?>
<br>

<?php echo render('main/_form_sign_in'); ?>

<hr>

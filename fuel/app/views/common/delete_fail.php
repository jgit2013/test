<br>
<p>
    <?php if (Session::get('is_admin') == '1'): ?>
        <?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-success')); ?>
    <?php else: ?>
        <?php echo Html::anchor('user', 'Back', array('class' => 'btn btn-success')); ?>
    <?php endif ?>
</p>
<hr>

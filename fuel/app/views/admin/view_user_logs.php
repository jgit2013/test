<?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-success')); ?>
<hr>
<h2>Find <span class='muted'>Logs</span></h2>
    <?php echo render('admin/_form_find_user_logs'); ?>
<hr>

<h2>Listing <span class='muted'>User Logs</span></h2>
<?php if (isset($found_user_logs)): ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>IP Address</th>
                <th>Username</th>
                <th>Sign In Time</th>
                <th>Sign Out Time</th>
                <th>During</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($found_user_logs as $found_user_log): ?>
                <tr>
                    <td><?php echo $found_user_log->ip_address; ?></td>
                    <td><?php echo $found_user_log->username; ?></td>
                    <td><?php echo $found_user_log->sign_in_time; ?></td>
                    <td><?php echo $found_user_log->sign_out_time; ?></td>
                    <td><?php echo $found_user_log->during; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No Logs.</p>
<?php endif; ?><p>
<hr>

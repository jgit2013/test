<?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-success')); ?>
<hr>
<h2>Find <span class='muted'>Logs</span></h2>
    <?php echo render('admin/_form_find_message_logs'); ?>
<hr>

<h2>Listing <span class='muted'>Logs</span></h2>
<?php if ($found_message_logs): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Time</th>
			<th>Username</th>
			<th>Action</th>
			<th>Before Title</th>
			<th>After Title</th>
			<th>Before Message</th>
			<th>After Message</th>
			<th>Is Succeed</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
        <?php foreach ($found_message_logs as $found_message_log): ?>
            <tr>
                <td><?php echo $found_message_log->time; ?></td>
                <td><?php echo $found_message_log->username; ?></td>
                <td><?php echo $found_message_log->action; ?></td>
                <td><?php echo $found_message_log->before_title; ?></td>
                <td><?php echo $found_message_log->after_title; ?></td>
                <td><?php echo $found_message_log->before_message; ?></td>
                <td><?php echo $found_message_log->after_message; ?></td>
                <td><?php echo $found_message_log->is_succeed; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p>No Logs.</p>
<?php endif; ?><p>
<hr>

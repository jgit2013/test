<?php echo Html::anchor('admin', 'Back', array('class' => 'btn btn-success')); ?>
<hr>
<h2>Find <span class='muted'>Logs</span></h2>
<?php echo render('admin/_form_find_logs'); ?>
<hr>

<h2>Listing <span class='muted'>Logs</span></h2>
<?php if ($logs): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Time</th>
			<th>Username</th>
			<th>Sign In Time</th>
			<th>Sign Out Time</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($logs as $log): ?>		<tr>
			<td><?php echo $log->time; ?></td>
			<td><?php echo $log->username; ?></td>
			<td><?php echo $log->sign_in_time; ?></td>
			<td><?php echo $log->sign_out_time; ?></td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>
<?php else: ?>
<p>No Logs.</p>
<?php endif; ?><p>
<hr>

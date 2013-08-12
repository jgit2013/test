<h2>Listing <span class='muted'>Messages</span></h2>
<br>
<?php if ($messages): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Time</th>
			<th>User</th>
			<th>Title</th>
			<th>Message</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
    <?php foreach ($messages as $message): ?>		<tr>
			<td><?php echo $message->time; ?></td>
			<td><?php echo $message->username; ?></td>
			<td><?php echo $message->title; ?></td>
			<td><?php echo $message->message; ?></td>
			<td>
				<?php echo Html::anchor('message/view/'.$message->id, '<i class="icon-eye-open" title="View"></i>'); ?>
				<?php if ($message->username == Session::get('username')): ?>
				     | <?php echo Html::anchor('message/edit/'.$message->id, '<i class="icon-wrench" title="Edit"></i>'); ?>
				     | <?php echo Html::anchor('message/delete/'.$message->id, '<i class="icon-trash" title="Delete"></i>', array('onclick' => "return confirm('Are you sure?')")); ?>
				<?php endif; ?>
			</td>
		</tr>
    <?php endforeach; ?>	</tbody>
</table>
<?php else: ?>
    <p>No Messages.</p>
<?php endif; ?><p>
	<?php echo Html::anchor('message/create', 'Add New Message', array('class' => 'btn btn-success')); ?>
	<?php echo Html::anchor('main/logout', 'Sign Out', array('class' => 'btn btn-success')); ?>
</p>
<hr>
